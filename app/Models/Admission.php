<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSlug; // same trait you’re using for AdminOffice

class Admission extends Model
{
    use HasFactory, HasSlug;

    protected $slugSourceColumn = 'title';

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'type',
        'external_url',
        'banner_image',
        'content',
        'meta_title',
        'meta_tags',
        'meta_description',
        'position',
        'status',
    ];

    public function children()
    {
        return $this->hasMany(Admission::class, 'parent_id')->orderBy('position');
    }

    public function recursiveChildren()
    {
        return $this->children()->with('recursiveChildren');
    }

    public function parent()
    {
        return $this->belongsTo(Admission::class, 'parent_id');
    }

    /**
     * Build breadcrumbs for frontend
     */
    public function breadcrumbs()
    {
        $crumbs = [];
        $node = $this;

        while ($node) {
            array_unshift($crumbs, [
                'title' => $node->title,
                'slug'  => $node->slug,
            ]);

            $node = $node->parent;
        }

        return $crumbs;
    }


    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function isMenu(): bool
    {
        return $this->type === 'menu';
    }

    public function isPage(): bool
    {
        return $this->type === 'page';
    }

    public function isExternal(): bool
    {
        return $this->type === 'external';
    }
}
