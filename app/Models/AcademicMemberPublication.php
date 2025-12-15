<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicMemberPublication extends Model
{
    protected $fillable = [
        'academic_staff_member_id',
        'title',
        'type',
        'journal_or_conference_name',
        'publisher',
        'year',
        'doi',
        'url',
        'position',
    ];

    public function member()
    {
        return $this->belongsTo(AcademicStaffMember::class, 'academic_staff_member_id');
    }
}
