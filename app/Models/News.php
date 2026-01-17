<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory, HasSlug;
    protected $table = 'news';
    protected $slugSourceColumn = 'title';


    protected $fillable = [
        'title',
        'slug',
        'thumb_image',
        'content_image',
        'banner_image',
        'summary',
        'content',
        'author',
        'published_at',
        'read_time',
        'category',
        'tags',
        'status',
        'owner_type',
        'owner_id',
    ];

    protected $casts = [
        'tags'         => 'array',
        'is_featured'  => 'boolean',
        'published_at' => 'date',
    ];
}
