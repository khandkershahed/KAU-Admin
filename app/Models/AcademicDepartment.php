<?php

namespace App\Models;

use App\Enums\AcademicStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_site_id',
        'title',
        'short_code',
        'slug',
        'description',
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

    public function staffSections()
    {
        return $this->hasMany(AcademicStaffSection::class, 'academic_department_id')
                    ->orderBy('position');
    }
}
