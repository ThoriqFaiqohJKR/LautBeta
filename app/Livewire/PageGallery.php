<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PageGallery extends Component
{
    public array $items = [];

    public function mount(): void
    {
        $loc = app()->getLocale();

        $rows = DB::table('gallery')
            ->leftJoin('database', 'gallery.database_id', '=', 'database.id')
            ->select([
                'gallery.id',
                'gallery.title_en',
                'gallery.title_id',
                'gallery.type',
                'gallery.file_id',
                'gallery.file_en',
                'gallery.status',
                'gallery.publikasi',
                'database.title_en as db_en',
                'database.title_id as db_id',
            ])
            ->where('gallery.status', 'on')
            ->where('gallery.publikasi', 'publish')
            ->whereIn('gallery.type', ['photo', 'video'])
            ->orderByDesc('gallery.id')
            ->get();

        $this->items = $rows
            ->map(function ($r) use ($loc) {
                $title = $this->pickLocale($r->title_en, $r->title_id, $loc);
                $cat   = $this->pickLocale($r->db_en, $r->db_id, $loc);
                $file  = $this->pickLocale($r->file_en, $r->file_id, $loc);

                if ($r->type === 'photo') {
                    $id = $this->getDriveId($file);
                    $photo = $id ? "https://drive.google.com/thumbnail?id={$id}" : $file;
                    return [
                        'id'       => $r->id,
                        'category' => $cat,
                        'title'    => $title,
                        'type'     => 'photo',
                        'photo'    => $photo,
                    ];
                }

                return [
                    'id'       => $r->id,
                    'category' => $cat,
                    'title'    => $title,
                    'type'     => 'video',
                    'photo'    => $file,
                ];
            })
            ->groupBy(fn($r) => $r['category'] ?: 'Uncategorized')
            ->map(fn($g) => [
                'photo' => $g->where('type', 'photo')->values()->toArray(),
                'video' => $g->where('type', 'video')->values()->toArray(),
            ])
            ->toArray();
    }

    private function getDriveId(?string $url): ?string
    {
        if (!$url) return null;
        if (str_contains($url, '/d/')) {
            $rest = explode('/d/', $url)[1] ?? '';
            return $rest ? strtok($rest, '/') : null;
        }
        if (str_contains($url, 'id=')) {
            $rest = explode('id=', $url)[1] ?? '';
            return $rest ? strtok($rest, '&') : null;
        }
        if (strlen($url) > 10 && !str_contains($url, '/')) {
            return $url;
        }
        return null;
    }

    private function pickLocale(?string $en, ?string $id, string $loc): ?string
    {
        return $loc === 'id' ? ($id ?: $en) : ($en ?: $id);
    }

    public function render()
    {
        return view('livewire.page-gallery', ['items' => $this->items]);
    }
}
