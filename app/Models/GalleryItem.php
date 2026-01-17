<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model {
    protected $fillable = ['gallery_id','item_type','media_path','video_url','title','position'];
}
