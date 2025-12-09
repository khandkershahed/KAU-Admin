<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicDepartment extends Model
{
    protected $fillable = [
        'academic_site_id',
        'title',
        'short_code',
        'slug',
        'description',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(AcademicSite::class, 'academic_site_id');
    }

    public function staffSections()
    {
        return $this->hasMany(AcademicStaffSection::class, 'academic_department_id')
                    ->orderBy('position')
                    ->orderBy('id');
    }
}
