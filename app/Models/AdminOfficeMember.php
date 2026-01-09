<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminOfficeMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'section_id',
        'name',
        'designation',
        'email',
        'phone',
        'label',
        'image',
        'type',
        'position'
    ];

    public function office()
    {
        return $this->belongsTo(AdminOffice::class, 'office_id');
    }

    public function section()
    {
        return $this->belongsTo(AdminOfficeSection::class, 'section_id');
    }
}
