<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicStaffMember extends Model
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
        return $this->belongsTo(AcademicStaffSection::class, 'staff_section_id');
    }
}
