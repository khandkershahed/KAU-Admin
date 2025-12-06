<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasSlug;

class AdminOfficeSection extends Model
{
    use HasFactory;


    protected $fillable = [
        'office_id',
        'title',
        'section_type',
        'content',
        'extra',
        'position',
        'status'
    ];

    protected $casts = [
        'extra' => 'array'
    ];

    public function office()
    {
        return $this->belongsTo(AdminOffice::class, 'office_id');
    }

    public function members()
    {
        return $this->hasMany(AdminOfficeMember::class, 'section_id')
            ->orderBy('position', 'asc');
    }
}
