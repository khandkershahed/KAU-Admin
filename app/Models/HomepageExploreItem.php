<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageExploreItem extends Model
{
    protected $table = 'homepage_explore_items';

    protected $fillable = [
        'explore_id',
        'icon',
        'title',
        'url',
        'position',
    ];

    public function explore()
    {
        return $this->belongsTo(HomepageExplore::class, 'explore_id');
    }
}
