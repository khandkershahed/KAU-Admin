<?php

namespace App\Console\Commands;

use App\Models\SearchIndex;
use Illuminate\Console\Command;

class RebuildSearchIndex extends Command
{
    protected $signature = 'cms:rebuild-search-index {--chunk=200 : Chunk size for batch processing}';

    protected $description = 'Rebuild the search_index table from published CMS content.';

    public function handle(): int
    {
        $chunk = (int) $this->option('chunk');
        if ($chunk <= 0) $chunk = 200;

        $this->info('Rebuilding search index...');
        $bar = $this->output->createProgressBar();
        $bar->start();

        SearchIndex::truncate();

        // Pages (Academic CMS)
        $this->indexAcademicPages($chunk, $bar);

        // Notices, News, Events, Tenders
        $this->indexPostLike('notice', '\\App\\Models\\Notice', '/notice/', $chunk, $bar);
        $this->indexPostLike('news', '\\App\\Models\\News', '/news/', $chunk, $bar);
        $this->indexPostLike('event', '\\App\\Models\\Event', '/events/', $chunk, $bar);
        $this->indexPostLike('tender', '\\App\\Models\\Tender', '/tender/', $chunk, $bar);

        $bar->finish();
        $this->newLine();
        $this->info('Done.');

        return self::SUCCESS;
    }

    protected function indexAcademicPages(int $chunk, $bar): void
    {
        if (!class_exists('\\App\\Models\\AcademicPage')) return;
        if (!class_exists('\\App\\Models\\AcademicSite')) return;
        if (!class_exists('\\App\\Models\\AcademicDepartment')) return;

        $Page = '\\App\\Models\\AcademicPage';
        $Site = '\\App\\Models\\AcademicSite';
        $Dept = '\\App\\Models\\AcademicDepartment';

        $Page::query()
            ->where('status', 'published')
            ->orderBy('id')
            ->chunk($chunk, function ($rows) use ($bar, $Site, $Dept) {
                foreach ($rows as $page) {
                    $url = null;

                    try {
                        $ownerType = $page->owner_type ?? null;
                        $ownerId   = $page->owner_id ?? null;

                        if ($ownerType === 'main' || $ownerType === null) {
                            $url = '/page/' . ltrim((string) $page->slug, '/');
                        } elseif ($ownerType === 'site') {
                            $site = $Site::find($ownerId);
                            if ($site) {
                                $url = '/' . $site->slug . '/' . ltrim((string) $page->slug, '/');
                            }
                        } elseif ($ownerType === 'department') {
                            $dept = $Dept::find($ownerId);
                            if ($dept) {
                                $site = $Site::find($dept->academic_site_id ?? null);
                                if ($site) {
                                    $url = '/' . $site->slug . '/' . $dept->slug . '/' . ltrim((string) $page->slug, '/');
                                }
                            }
                        } elseif ($ownerType === 'office') {
                            $url = null;
                        } else {
                            $url = null;
                        }
                    } catch (\Throwable $e) {
                        $url = null;
                    }

                    SearchIndex::create([
                        'entity_type'  => 'page',
                        'entity_id'    => $page->id,
                        'owner_type'   => $page->owner_type ?? null,
                        'owner_id'     => $page->owner_id ?? null,
                        'title'        => (string) ($page->title ?? ''),
                        'body'         => $this->stripText($page->content ?? ''),
                        'url'          => $url,
                        'published_at' => $page->published_at ?? null,
                        'status'       => $page->status ?? null,
                    ]);

                    $bar->advance();
                }
            });
    }

    protected function indexPostLike(string $entityType, string $modelClass, string $baseUrl, int $chunk, $bar): void
    {
        if (!class_exists($modelClass)) return;

        $modelClass::query()
            ->where('status', 'published')
            ->orderBy('id')
            ->chunk($chunk, function ($rows) use ($entityType, $baseUrl, $bar) {
                foreach ($rows as $row) {
                    $slug = (string) ($row->slug ?? '');
                    $url = $slug ? ($baseUrl . $slug) : null;

                    SearchIndex::create([
                        'entity_type'  => $entityType,
                        'entity_id'    => $row->id,
                        'owner_type'   => $row->owner_type ?? 'main',
                        'owner_id'     => $row->owner_id ?? null,
                        'title'        => (string) ($row->title ?? ''),
                        'body'         => $this->stripText($row->content ?? ($row->description ?? ($row->body ?? ''))),
                        'url'          => $url,
                        'published_at' => $row->published_at ?? ($row->date ?? null),
                        'status'       => $row->status ?? null,
                    ]);

                    $bar->advance();
                }
            });
    }

    protected function stripText($html): string
    {
        $text = strip_tags((string) $html);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        return trim((string) $text);
    }
}
