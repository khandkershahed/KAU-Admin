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
        'subdomain',
        'short_description',
        'theme_primary_color',
        'theme_secondary_color',
        'logo_path',
        'menu_order',
        'status',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function group()
    {
        return $this->belongsTo(AcademicMenuGroup::class, 'academic_menu_group_id');
    }

    public function navItems()
    {
        return $this->hasMany(AcademicNavItem::class, 'academic_site_id')
            ->orderBy('position')
            ->orderBy('id');
    }

    public function pages()
    {
        return $this->hasMany(AcademicPage::class, 'academic_site_id')
            ->orderBy('position')
            ->orderBy('id');
    }

    public function departments()
    {
        return $this->hasMany(AcademicDepartment::class, 'academic_site_id')
            ->orderBy('position')
            ->orderBy('id');
    }

    public function staffSections()
    {
        return $this->hasMany(AcademicStaffSection::class, 'academic_site_id')
            ->orderBy('position')
            ->orderBy('id');
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }
}
