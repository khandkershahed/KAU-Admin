<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageAbout extends Model
{
    protected $table = 'homepage_about';

    protected $fillable = [
        'badge',
        'title',
        'subtitle',
        'description',
        'experience_badge',
        'experience_title',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function getImagesArrayAttribute()
    {
        $images = $this->images ?? [];
        if (!is_array($images)) {
            return [];
        }
        return $images;
    }
}
