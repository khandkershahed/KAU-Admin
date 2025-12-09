<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicStaffGroup extends Model
{
    protected $fillable = [
        'academic_site_id',
        'academic_department_id',
        'title',
        'position',
    ];

    public function site()
    {
        return $this->belongsTo(AcademicSite::class, 'academic_site_id');
    }

    public function department()
    {
        return $this->belongsTo(AcademicDepartment::class, 'academic_department_id');
    }

    public function members()
    {
        return $this->hasMany(AcademicStaffMember::class, 'staff_group_id')
                    ->orderBy('position');
    }
}
