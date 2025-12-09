<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPageSection extends Model
{
    protected $fillable = [
        'academic_page_id',
        'section_key',
        'title',
        'subtitle',
        'content',
        'extra',
        'position',
        'is_active',
    ];

    protected $casts = [
        'extra'     => 'array',
        'is_active' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(AcademicPage::class, 'academic_page_id');
    }
}
