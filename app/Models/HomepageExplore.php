<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageExplore extends Model
{
    protected $table = 'homepage_explore';

    protected $fillable = [
        'section_title',
    ];

    public function items()
    {
        return $this->hasMany(HomepageExploreItem::class, 'explore_id')
                    ->orderBy('position');
    }
}
