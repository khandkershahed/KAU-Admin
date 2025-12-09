<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicStaffMember extends Model
{
    protected $fillable = [
        'staff_group_id',
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

    public function group()
    {
        return $this->belongsTo(AcademicStaffGroup::class, 'staff_group_id');
    }
}
