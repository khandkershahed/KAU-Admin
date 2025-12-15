<?php

namespace App\Models;

use App\Enums\AcademicStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicStaffMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_section_id',
        'name',
        'designation',
        'email',
        'phone',
        'image_path',
        'position',
        'status',
        'links',
        'uuid',
        'mobile',
        'address',
        'research_interest',
        'bio',
        'education',
        'experience',
        'scholarship',
        'research',
        'teaching',
    ];

    protected $casts = [
        // 'status' => AcademicStatus::class,
        'links' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(AcademicStaffSection::class, 'staff_section_id');
    }
    public function publications()
    {
        return $this->hasMany(AcademicMemberPublication::class, 'academic_staff_member_id')
            ->orderBy('position')
            ->orderBy('id');
    }

    
}
