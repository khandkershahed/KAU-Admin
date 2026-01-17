<?php

namespace App\Models;

use App\Enums\AcademicStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicNavItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_site_id',
        // owner-based menus (main / site / department / office)
        'owner_type',
        'owner_id',
        'parent_id',
        'label',
        'slug',
        'menu_key',
        'type',          // route|page|external|group
        'external_url',
        'menu_location', // navbar|topbar
        'icon',
        'position',
        'status',
    ];

    protected $casts = [
        // 'status' => AcademicStatus::class,
    ];

    public function site()
    {
        return $this->belongsTo(AcademicSite::class, 'academic_site_id');
    }

    public function parent()
    {
        return $this->belongsTo(AcademicNavItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AcademicNavItem::class, 'parent_id')
                    ->orderBy('position');
    }

    public function page()
    {
        return $this->hasOne(AcademicPage::class, 'nav_item_id');
    }

    // Check before delete (Option B)
    public function canBeDeleted(): bool
    {
        return $this->page === null;
    }

    // Build recursive tree structure
    public static function getTreeForSite($siteId)
    {
        $items = self::where('academic_site_id', $siteId)
                     ->orderBy('position')
                     ->get()
                     ->groupBy('parent_id');

        $buildTree = function ($parentId) use (&$buildTree, $items) {
            return ($items[$parentId] ?? collect())->map(function ($item) use (&$buildTree) {
                return [
                    'model' => $item,
                    'children' => $buildTree($item->id),
                ];
            })->toArray();
        };

        return $buildTree(null);
    }
}
