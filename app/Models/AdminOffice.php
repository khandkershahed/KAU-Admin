<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasSlug;

class AdminOffice extends Model
{
    use HasFactory, HasSlug;

    protected $slugSourceColumn = 'title';

    protected $fillable = [
        'group_id',
        'title',
        'slug',
        'banner_image',
        'description',
        'meta_title',
        'meta_tags',
        'meta_description',
        'position',
        'status'
    ];

    public function group()
    {
        return $this->belongsTo(AdminGroup::class, 'group_id');
    }

    public function sections()
    {
        return $this->hasMany(AdminOfficeSection::class, 'office_id')
            ->orderBy('position', 'asc');
    }

    public function members()
    {
        return $this->hasMany(AdminOfficeMember::class, 'office_id')
            ->orderBy('position', 'asc');
    }
}
