<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    use HasFactory, HasSlug;

    protected $slugSourceColumn = 'title';

    protected $fillable = [
        'title',
        'slug',
        'menu_label',
        'banner_title',
        'banner_subtitle',
        'banner_icon',
        'banner_image',
        'excerpt',
        'content',
        'menu_order',
        'is_featured',
        'status',
        'meta_title',
        'meta_tags',
        'meta_description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    /* SCOPES */

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('menu_order')->orderBy('id');
    }
}
