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
        'is_home',
        'is_department_boxes',
        'banner_image',
        'content',
        'meta_title',
        'meta_tags',
        'meta_description',
        'og_image',
        'banner_title',
        'banner_subtitle',
        'banner_button',
        'banner_button_url',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_home'   => 'boolean',
        'is_department_boxes'   => 'boolean',
        'is_active' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(AcademicSite::class, 'academic_site_id');
    }

    public function navItem()
    {
        return $this->belongsTo(AcademicNavItem::class, 'nav_item_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
