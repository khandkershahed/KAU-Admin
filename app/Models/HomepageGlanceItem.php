<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageGlanceItem extends Model
{
     protected $table = 'homepage_glance_items';

    protected $fillable = [
        'glance_id',
        'icon',
        'title',
        'number',
        'position',
    ];

    public function glance()
    {
        return $this->belongsTo(HomepageGlance::class, 'glance_id');
    }
}
