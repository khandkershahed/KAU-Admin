<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = [
        'section_key',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const KEY_BANNER   = 'banner';
    public const KEY_VC       = 'vc_message';
    public const KEY_EXPLORE  = 'explore';
    public const KEY_FACULTY  = 'faculty';
    public const KEY_GLANCE   = 'glance';
    public const KEY_ABOUT    = 'about';
}
