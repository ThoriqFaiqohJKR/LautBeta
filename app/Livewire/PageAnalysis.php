<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageAnalysis extends Component
{
    public array $items = [];

    public function mount(): void
    {
        $rows = DB::table('insight')
            ->select('id', 'image', 'title_en', 'title_id', 'description_en', 'description_id', 'slug', 'tanggal_publikasi', 'publikasi', 'status', 'type')
            ->where(DB::raw('LOWER(type)'), 'analysis')
            ->where('publikasi', 'publish')
            ->where('status', 'on')
            ->orderByDesc('tanggal_publikasi')
            ->orderByDesc('id')
            ->get();

        $loc = app()->getLocale();

        $this->items = $rows->map(function ($r) use ($loc) {
            $title = $loc === 'id' ? ($r->title_id ?: $r->title_en) : ($r->title_en ?: $r->title_id);
            $descRaw = $loc === 'id' ? ($r->description_id ?: $r->description_en) : ($r->description_en ?: $r->description_id);
            return [
                'id'          => $r->id,
                'title'       => Str::words($title ?: 'Untitled', 150, '...'),
                'description' => $descRaw,
                'image_url'   => $this->imageUrl($r->image),
                'slug'        => $r->slug ?: Str::slug($title ?: 'analysis-' . $r->id),
            ];
        })->toArray();
    }

    private function imageUrl(?string $path): string
    {
        if (!$path) return asset('img/placeholder-16x9.png');
        if (preg_match('~^https?://~i', $path)) return $path;
        if (Storage::disk('public')->exists($path)) return Storage::url($path);
        return asset(ltrim($path, '/'));
    }

    public function render()
    {
        return view('livewire.page-analysis');
    }
}
