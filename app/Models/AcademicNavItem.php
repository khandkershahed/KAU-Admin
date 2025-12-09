<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicNavItem extends Model
{
    protected $fillable = [
        'academic_site_id',
        'parent_id',
        'label',
        'menu_key',
        'type',
        'page_id',
        'route_name',
        'external_url',
        'icon',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(AcademicSite::class, 'academic_site_id');
    }

    public function page()
    {
        return $this->belongsTo(AcademicPage::class, 'page_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('position');
    }
}
