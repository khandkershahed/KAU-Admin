<?php

namespace App\Models;

use App\Enums\AcademicStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_menu_group_id',
        'name',
        'short_name',
        'slug',
        'short_description',
        'theme_primary_color',
        'theme_secondary_color',
        'logo_path',
        'position',
        'status',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
        // 'status' => AcademicStatus::class,
    ];

    public function group()
    {
        return $this->belongsTo(AcademicMenuGroup::class, 'academic_menu_group_id');
    }

    public function navItems()
    {
        return $this->hasMany(AcademicNavItem::class, 'academic_site_id')
                    ->orderBy('position');
    }

    public function pages()
    {
        return $this->hasMany(AcademicPage::class, 'academic_site_id')->orderBy('position');
    }

    public function departments()
    {
        return $this->hasMany(AcademicDepartment::class, 'academic_site_id')->orderBy('position');
    }

    public function staffSections()
    {
        return $this->hasMany(AcademicStaffSection::class, 'academic_site_id')->orderBy('position');
    }
}
