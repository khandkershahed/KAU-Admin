<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model {
    protected $fillable = ['owner_type','owner_id','title','slug','type','is_active','position'];
    public function items() {
        return $this->hasMany(GalleryItem::class)->orderBy('position');
    }
}
