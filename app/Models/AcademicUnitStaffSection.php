<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicUnitStaffSection extends Model
{
    protected $fillable = [
        'academic_unit_id',
        'department_id',
        'title',
        'position',
    ];

    public function unit()
    {
        return $this->belongsTo(AcademicUnit::class, 'academic_unit_id');
    }

    public function department()
    {
        return $this->belongsTo(AcademicUnitDepartment::class, 'department_id');
    }

    public function members()
    {
        return $this->hasMany(AcademicUnitStaffMember::class, 'staff_section_id')
                    ->orderBy('position')
                    ->orderBy('id');
    }
}
