<?php

namespace App\Jobs;

use App\Models\SearchIndex;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexPublishedContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $entityType;
    public string $modelClass;
    public int $entityId;
    public ?string $urlPrefix;

    public function __construct(string $entityType, string $modelClass, int $entityId, ?string $urlPrefix = null)
    {
        $this->entityType = $entityType;
        $this->modelClass = $modelClass;
        $this->entityId   = $entityId;
        $this->urlPrefix  = $urlPrefix;
    }

    public function handle(): void
    {
        if (!class_exists($this->modelClass)) return;

        $row = $this->modelClass::find($this->entityId);

        // Delete if missing or not published
        if (!$row || ($row->status ?? null) !== 'published') {
            SearchIndex::where('entity_type', $this->entityType)
                ->where('entity_id', $this->entityId)
                ->delete();
            return;
        }

        $slug = (string) ($row->slug ?? '');
        $url = ($this->urlPrefix && $slug) ? ($this->urlPrefix . $slug) : ($row->url ?? null);

        SearchIndex::updateOrCreate(
            ['entity_type' => $this->entityType, 'entity_id' => $this->entityId],
            [
                'owner_type'   => $row->owner_type ?? 'main',
                'owner_id'     => $row->owner_id ?? null,
                'title'        => (string) ($row->title ?? ''),
                'body'         => $this->stripText($row->content ?? ($row->description ?? ($row->body ?? ''))),
                'url'          => $url,
                'published_at' => $row->published_at ?? ($row->date ?? null),
                'status'       => $row->status ?? null,
            ]
        );
    }

    protected function stripText($html): string
    {
        $text = strip_tags((string) $html);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        return trim((string) $text);
    }
}
