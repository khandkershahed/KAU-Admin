<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicMemberPublication extends Model
{
    protected $fillable = [
        'academic_staff_member_id',
        'title',
        'type',
        'category',
        'journal_or_conference_name',
        'publisher',
        'year',
        'doi',
        'url',
        'position',
    ];

    protected $casts = [
        'year' => 'integer',
        'position' => 'integer',
    ];

    public function member()
    {
        return $this->belongsTo(AcademicStaffMember::class, 'academic_staff_member_id');
    }
}
