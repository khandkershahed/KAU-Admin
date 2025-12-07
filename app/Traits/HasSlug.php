<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (!isset($model->slugSourceColumn)) {
                throw new \InvalidArgumentException('Slug source column is not defined in the model.');
            }

            $model->slug = $model->generateUniqueSlug($model->{$model->slugSourceColumn});
        });

        static::updating(function ($model) {
            if (!isset($model->slugSourceColumn)) {
                throw new \InvalidArgumentException('Slug source column is not defined in the model.');
            }

            $model->slug = $model->generateUniqueSlug($model->{$model->slugSourceColumn});
        });
    }

    /**
     * Generate unique slug (unicode-safe)
     */
    private function generateUniqueSlug(string $value): string
    {
        $baseSlug = $this->makeUnicodeSlug($value);
        $slug     = $baseSlug;
        $counter  = 1;

        while ($this->slugExists($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Create a Unicode-safe slug and limit its length.
     */
    private function makeUnicodeSlug(string $value): string
    {
        // If ASCII only, use Laravel default
        if (preg_match('/^[\x20-\x7E]+$/', $value)) {
            return Str::slug($value);
        }

        // Unicode slug (Bangla)
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $value);
        $slug = trim($slug, '-');
        $slug = mb_strtolower($slug, 'UTF-8');

        // 💡 Limit final slug length (safe & SEO-friendly)
        $maxLength = 58; // Change this if needed

        if (mb_strlen($slug, 'UTF-8') > $maxLength) {
            // Prefer trimming at word boundary
            $trimmed = mb_substr($slug, 0, $maxLength, 'UTF-8');

            // If cutting in the middle of a word, cut back to previous "-"
            $lastDash = mb_strrpos($trimmed, '-', 0, 'UTF-8');
            if ($lastDash !== false) {
                $trimmed = mb_substr($trimmed, 0, $lastDash, 'UTF-8');
            }

            $slug = rtrim($trimmed, '-');
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
