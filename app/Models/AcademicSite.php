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

    protected $casts = [
        'config' => 'array',
    ];

    public function group()
    {
        return $this->belongsTo(AcademicMenuGroup::class, 'academic_menu_group_id');
    }

    public function navItems()
    {
        return $this->hasMany(AcademicNavItem::class)->orderBy('position');
    }

    public function pages()
    {
        return $this->hasMany(AcademicPage::class)->orderBy('position');
    }

    public function departments()
    {
        return $this->hasMany(AcademicDepartment::class)->orderBy('position');
    }

    public function staffGroups()
    {
        return $this->hasMany(AcademicStaffGroup::class)->orderBy('position');
    }

    public function homeWidgets()
    {
        return $this->hasMany(AcademicHomeWidget::class)->orderBy('position');
    }

    public function scopeSlug($q, string $slug)
    {
        return $q->where('slug', $slug);
    }
}
