<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory, HasSlug;

    protected $slugSourceColumn = 'title';

    protected $guarded = [];

    protected $casts = [
        'attachments'  => 'array',
        'publish_date' => 'date',
        'closing_date' => 'date',
        'is_featured'  => 'boolean',
    ];

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
