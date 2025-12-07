<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'category',
        'tag',
        'order',
        'status',
        'views',
        'related_faqs',
        'is_featured',
        'additional_info',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'related_faqs' => 'array',
        'is_featured' => 'boolean',
    ];
}
