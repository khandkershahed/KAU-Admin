<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPageBlock extends Model
{
    protected $table = 'academic_page_blocks';

    protected $fillable = [
        'academic_page_id',
        'block_type',
        'data',
        'position',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(AcademicPage::class, 'academic_page_id');
    }
}
