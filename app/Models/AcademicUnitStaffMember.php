<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicUnitStaffMember extends Model
{
    protected $fillable = [
        'staff_section_id',
        'name',
        'designation',
        'email',
        'phone',
        'image_path',
        'position',
        'links',
    ];

    protected $casts = [
        'links' => 'array',   
    ];

    public function section()
    {
        return $this->belongsTo(AcademicUnitStaffSection::class, 'staff_section_id');
    }
}
