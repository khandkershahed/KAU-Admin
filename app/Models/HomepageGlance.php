<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageGlance extends Model
{
    protected $table = 'homepage_glance';

    protected $fillable = [
        'section_title',
        'section_subtitle',
    ];

    public function items()
    {
        return $this->hasMany(HomepageGlanceItem::class, 'glance_id')->orderBy('position');
    }
}
