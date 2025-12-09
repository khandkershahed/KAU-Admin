<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicMenuGroup extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sites()
    {
        return $this->hasMany(AcademicSite::class, 'academic_menu_group_id')
                    ->orderBy('menu_order')
                    ->orderBy('id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('position')->orderBy('id');
    }
}
