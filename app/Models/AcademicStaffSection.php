<?php

namespace App\Models;

use App\Enums\AcademicStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicStaffSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_site_id',
        'academic_department_id',
        'title',
        'position',
        'status',
    ];

    protected $casts = [
        // 'status' => AcademicStatus::class,
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
        return $this->hasMany(AcademicStaffMember::class, 'staff_section_id')
                    ->orderBy('position');
    }
}
