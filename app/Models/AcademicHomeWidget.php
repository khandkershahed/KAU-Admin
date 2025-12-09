<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicHomeWidget extends Model
{
    protected $fillable = [
        'academic_site_id',
        'widget_type',
        'title',
        'subtitle',
        'content',
        'image_path',
        'button_text',
        'button_url',
        'icon',
        'extra',
        'position',
        'is_active',
    ];

    protected $casts = [
        'extra'     => 'array',
        'is_active' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(AcademicSite::class, 'academic_site_id');
    }
}
