<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicUnit extends Model
{
    protected $fillable = [
        'academic_menu_group_id',
        'icon',
        'name',
        'slug',
        'short_name',
        'short_description',
        'button_name',
        'menu_order',
        'base_url',
        'home_layout',
        'home_has_hero',
        'home_has_department_grid',
        'config',
        'status',
    ];

    protected $casts = [
        'home_has_hero'           => 'boolean',
        'home_has_department_grid'=> 'boolean',
        'config'                  => 'array',
    ];

    public function group()
    {
        return $this->belongsTo(AcademicMenuGroup::class, 'academic_menu_group_id');
    }

    public function departments()
    {
        return $this->hasMany(AcademicUnitDepartment::class, 'academic_unit_id')
                    ->orderBy('position')
                    ->orderBy('id');
    }

    public function staffSections()
    {
        return $this->hasMany(AcademicUnitStaffSection::class, 'academic_unit_id')
                    ->orderBy('position')
                    ->orderBy('id');
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }
}
