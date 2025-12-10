<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Enums\AcademicStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicMenuGroup extends Model
{
    use HasFactory, HasSlug;
    protected $slugSourceColumn = 'title';
    protected $fillable = [
        'title',
        'position',
        'status',
    ];

    protected $casts = [
        // 'status' => AcademicStatus::class,
    ];

    public function sites()
    {
        return $this->hasMany(AcademicSite::class, 'academic_menu_group_id')
            ->orderBy('position');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
