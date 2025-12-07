<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (!isset($model->slugSourceColumn)) {
                throw new \InvalidArgumentException("Slug source column is not defined.");
            }

            $model->slug = $model->generateUniqueSlug($model->{$model->slugSourceColumn});
        });

        static::updating(function ($model) {
            if (!isset($model->slugSourceColumn)) {
                throw new \InvalidArgumentException("Slug source column is not defined.");
            }

            $model->slug = $model->generateUniqueSlug($model->{$model->slugSourceColumn});
        });
    }

    /**
     * Generate a unique slug with SEO length (max 60 chars).
     */
    private function generateUniqueSlug(string $value): string
    {
        $baseSlug = $this->makeUnicodeSeoSlug($value);
        $slug     = $baseSlug;
        $counter  = 1;

        while ($this->slugExists($slug)) {
            $suffix = '-' . $counter;
            $slug   = mb_substr($baseSlug, 0, 60 - mb_strlen($suffix, 'UTF-8'), 'UTF-8') . $suffix;
            $counter++;
        }

        return $slug;
    }

    /**
     * Create Unicode slug and limit to max 60 chars for SEO.
     * Works for both Bangla & English.
     */
    private function makeUnicodeSeoSlug(string $value): string
    {
        $value = trim($value);

        // If it's pure ASCII, use Laravel's normal slug
        if (preg_match('/^[\x20-\x7E]+$/', $value)) {
            return Str::slug($value, '-', 'en');
        }

        // --- Unicode slug (Bangla etc.) ---

        // 1) Replace whitespace + separators + punctuation with '-'
        $slug = preg_replace('/[\s\p{Z}\p{P}]+/u', '-', $value);

        // 2) Remove anything that's NOT:
        //    Letter (\p{L}), Number (\p{N}), Mark (\p{M}), or '-'
        $slug = preg_replace('/[^\p{L}\p{N}\p{M}\-]+/u', '', $slug);

        // 3) Normalize multiple dashes and trim
        $slug = preg_replace('/-+/u', '-', $slug);
        $slug = trim($slug, '-');

        // 4) Lowercase (works with Bangla too)
        $slug = mb_strtolower($slug, 'UTF-8');

        // 5) Limit to max 60 characters
        $maxLength = 60;
        if (mb_strlen($slug, 'UTF-8') > $maxLength) {
            $short = mb_substr($slug, 0, $maxLength, 'UTF-8');

            // Try to cut at last dash to avoid breaking a word
            $lastDash = mb_strrpos($short, '-', 0, 'UTF-8');
            if ($lastDash !== false) {
                $short = mb_substr($short, 0, $lastDash, 'UTF-8');
            }

            $slug = rtrim($short, '-');
        }

        return $slug;
    }

    private function slugExists($slug): bool
    {
        return DB::table($this->getTable())
            ->where('slug', $slug)
            ->where('id', '!=', $this->id)
            ->exists();
    }
}
