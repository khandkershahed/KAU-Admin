<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicSite extends Model
{
    protected $fillable = [
        'academic_menu_group_id',
        'name',
        'short_name',
        'slug',
        'base_url',
        'short_description',
        'subdomain',
        'theme_primary_color',
        'theme_secondary_color',
        'logo_path',
        'menu_order',
        'status',
        'config',
    ];

    // if you want route-model binding by slug without {site:slug} syntax,
    // you can add this, but it's optional when you already use {site:slug}
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function group()
    {
        return $this->belongsTo(AcademicMenuGroup::class, 'academic_menu_group_id');
    }

    public function navItems()
    {
        return $this->hasMany(AcademicNavItem::class, 'academic_site_id');
    }

    public function homeWidgets()
    {
        return $this->hasMany(AcademicHomeWidget::class, 'academic_site_id');
    }

    public function departments()
    {
        return $this->hasMany(AcademicDepartment::class, 'academic_site_id');
    }
}
