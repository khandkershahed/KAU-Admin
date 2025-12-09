<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPage extends Model
{
    protected $fillable = [
        'academic_site_id',
        'nav_item_id',
        'page_key',
        'slug',
        'title',
        'subtitle',
        'page_type',
        'banner_title',
        'banner_subtitle',
        'banner_image_path',
        'layout_config',
        'meta_title',
        'meta_tags',
        'meta_description',
        'is_active',
        'position',
    ];

    protected $casts = [
        'layout_config' => 'array',
        'is_active'     => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(AcademicSite::class, 'academic_site_id');
    }

    public function navItem()
    {
        return $this->belongsTo(AcademicNavItem::class, 'nav_item_id');
    }

    public function sections()
    {
        return $this->hasMany(AcademicPageSection::class, 'academic_page_id')
                    ->orderBy('position')
                    ->orderBy('id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
