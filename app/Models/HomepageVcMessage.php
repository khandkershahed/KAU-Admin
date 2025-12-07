<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageVcMessage extends Model
{
    protected $table = 'homepage_vc_message';

    protected $fillable = [
        'vc_name',
        'vc_designation',
        'vc_image',
        'message_title',
        'message_text',
        'button_name',
        'button_url',
    ];
    public $timestamps = true;
}
