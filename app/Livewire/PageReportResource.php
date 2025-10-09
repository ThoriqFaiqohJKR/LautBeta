<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PageReportResource extends Component
{
    public array $items = [];

    public function mount(): void
    {

        $rows = DB::table('report')
            ->select([
                'id',
                'image',
                'title_en',
                'title_id',
                'description_en',
                'description_id',
                'slug',
            ])

            ->where('publikasi', 'publish')
            ->orderByDesc('id')
            ->get();

        $locale = app()->getLocale();

        $this->items = $rows->map(function ($r) use ($locale) {
            $title = $locale === 'id'
                ? ($r->title_id ?: $r->title_en)
                : ($r->title_en ?: $r->title_id);

            $description = $locale === 'id'
                ? ($r->description_id ?: $r->description_en)
                : ($r->description_en ?: $r->description_id);

            return [
                'id'          => $r->id,
                'title'       => $title ?: 'Untitled',
                'description' => $description,
                'image_url'   => $this->imageUrl($r->image),
                'slug'        => $r->slug ?? \Illuminate\Support\Str::slug($title),
            ];
        })->toArray();
    }

    private function imageUrl(?string $path): string
    {
        if (!$path) {
            return asset('img/placeholder-16x9.png');
        }

        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return asset(ltrim($path, '/'));
    }

    public function render()
    {
        return view('livewire.page-report-resource');
    }
}
