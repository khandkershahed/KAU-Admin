<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_site_id',
        'nav_item_id',
        'page_key',
        'slug',
        'title',

        // Added by migration: 2026_01_13_000110_add_owner_template_settings_to_academic_pages_table
        'owner_type',
        'owner_id',
        'template_key',
        'settings',

        'is_home',
        'is_department_boxes',
        'is_faculty_members',

        'banner_title',
        'banner_subtitle',
        'banner_button',
        'banner_button_url',
        'banner_image',

        'content',

        'meta_title',
        'meta_tags',
        'meta_description',
        'og_image',

        'status',
        'position',
    ];

    protected $casts = [
        'is_home'             => 'boolean',
        'is_department_boxes' => 'boolean',
        'is_faculty_members'  => 'boolean',
        'owner_id'            => 'integer',
        'settings'            => 'array',
    ];

    public function site()
    {
        return $this->belongsTo(AcademicSite::class, 'academic_site_id');
    }

    public function nav()
    {
        return $this->belongsTo(AcademicNavItem::class, 'nav_item_id');
    }

    public function blocks()
    {
        return $this->hasMany(AcademicPageBlock::class, 'academic_page_id')->orderBy('position');
    }
}
