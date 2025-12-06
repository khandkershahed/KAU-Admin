<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasSlug;

class AdminGroup extends Model
{
    use HasFactory, HasSlug;

    protected $slugSourceColumn = 'name';

    protected $fillable = [
        'name',
        'slug',
        'position',
        'status'
    ];

    public function offices()
    {
        return $this->hasMany(AdminOffice::class, 'group_id')
            ->orderBy('position', 'asc');
    }
}
