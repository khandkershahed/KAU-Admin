<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicUnitDepartment extends Model
{
    protected $fillable = [
        'academic_unit_id',
        'title',
        'short_code',
        'position',
    ];

    public function unit()
    {
        return $this->belongsTo(AcademicUnit::class, 'academic_unit_id');
    }
}
