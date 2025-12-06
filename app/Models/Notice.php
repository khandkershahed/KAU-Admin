<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory, HasSlug;
    protected $slugSourceColumn = 'name';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'attachments'   => 'array',
        'publish_date'  => 'date',
        'is_featured'   => 'boolean',
        'views'         => 'integer',
    ];

    public function noticeCategory()
    {
        return $this->belongsTo(NoticeCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getFirstAttachmentAttribute()
    {
        $attachments = $this->attachments ?? [];
        return count($attachments) ? $attachments[0] : null;
    }
}
