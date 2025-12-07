<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageFaculty extends Model
{
    protected $table = 'homepage_faculty';

    protected $fillable = [
        'section_title',
        'section_subtitle',
    ];
}
