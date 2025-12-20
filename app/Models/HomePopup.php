<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePopup extends Model
{
    use HasFactory, HasSlug;
    protected $slugSourceColumn = 'title';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'image_url',
        'badge',
        'button_name',
        'button_link',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];
}
