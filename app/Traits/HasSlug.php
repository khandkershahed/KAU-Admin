<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * Generate a unique slug for the given value.
     * Supports both ASCII (English) and Unicode (e.g. Bangla).
     */
    private function generateUniqueSlug($value)
    {
        $baseSlug = $this->makeSlug($value);
        $slug     = $baseSlug;
        $counter  = 1;

        while ($this->slugExists($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            Log::info("Trying slug: $slug");
        }

        return $slug;
    }

    /**
     * Build the base slug string.
     *
     * - If string is pure ASCII → use Str::slug (old behavior)
     * - If string contains non-ASCII (e.g. Bangla) → keep Unicode characters
     *   and only replace non letters/numbers with "-".
     */
    private function makeSlug(string $value): string
    {
        $value = trim($value);

        // If it is pure ASCII, use the default Laravel slug (for English etc.)
        if (preg_match('/^[\x20-\x7E]+$/', $value)) {
            return Str::slug($value);
        }

        // Unicode slug (for Bangla, etc.)
        // Keep all letters & numbers, replace everything else with "-"
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $value);
        $slug = trim($slug, '-');

        // Optional: lowercase – comment this out if you want original case
        $slug = mb_strtolower($slug, 'UTF-8');

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
