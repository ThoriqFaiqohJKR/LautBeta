<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Index extends Component
{
    public array $insights = [];
    public array $events = [];
    public ?array $ngopini = null;
    public array $infographics = [];

    public int $limitInsights = 2;
    public int $limitEvents = 2;
    public int $limitInfographics = 6;



    public function mount(): void
    {
        $this->loadInsights();
        $this->loadEvents();
        $this->loadNgopini();
        $this->loadInfographics();
    }

    protected function loadInsights(): void
    {
        $loc = app()->getLocale();

        $rows = DB::table('insight')
            ->select('id', 'slug', 'type', 'title_id', 'title_en', 'description_id', 'description_en', 'image', 'tanggal_publikasi', 'publikasi', 'status')
            ->where('publikasi', 'publish')
            ->where('status', 'on')
            ->whereIn(DB::raw('LOWER(type)'), ['feature', 'analysis'])
            ->orderByDesc('tanggal_publikasi')
            ->orderByDesc('id')
            ->limit($this->limitInsights)
            ->get();

        $this->insights = $rows->map(function ($r) use ($loc) {
            $title   = $loc === 'id' ? ($r->title_id ?? $r->title_en) : ($r->title_en ?? $r->title_id);
            $descRaw = $loc === 'id' ? ($r->description_id ?? $r->description_en) : ($r->description_en ?? $r->description_id);
            $desc    = strip_tags(html_entity_decode($descRaw ?? ''));
            $img     = $r->image ? (Str::startsWith($r->image, ['http://', 'https://']) ? $r->image : Storage::url($r->image)) : null;

            return [
                'id'    => $r->id,
                'slug'  => $r->slug,
                'type'  => strtoupper($r->type ?? 'INSIGHT'),
                'title' => $title ?: 'Untitled',
                'desc'  => $desc,
                'image' => $img,
                'url'   => url(app()->getLocale() . '/' . ($r->type === 'analysis' ? 'PageDetailAnalysis' : 'PageDetailFeature') . '/' . $r->id . '/' . $r->slug),
            ];
        })->toArray();
    }

    protected function loadEvents(): void
    {
        $loc = app()->getLocale();

        $rows = DB::table('agenda')
            ->where('type', 'event')
            ->where('publikasi', 'publish')
            ->where('status', 'on')
            ->orderByDesc('tanggal_publikasi')
            ->orderByDesc('id')
            ->limit($this->limitEvents)
            ->get();

        $this->events = $rows->map(function ($r) use ($loc) {
            $title = $loc === 'id'
                ? ($r->title_id ?? $r->title_en ?? 'Untitled')
                : ($r->title_en ?? $r->title_id ?? 'Untitled');

            $date = $r->tanggal_publikasi
                ? Carbon::parse($r->tanggal_publikasi)->format('d M Y')
                : null;

            $img = $r->image
                ? (Str::startsWith($r->image, ['http://', 'https://'])
                    ? $r->image
                    : Storage::url($r->image))
                : null;

            return [
                'id'    => $r->id,
                'slug'  => $r->slug,
                'title' => $title,
                'date'  => $date,
                'image' => $img,
                'url'   => url(app()->getLocale() . '/Pagedetailevent/' . $r->id . '/' . $r->slug),
            ];
        })->toArray();
    }

    protected function loadNgopini(): void
    {
        $loc = app()->getLocale();

        $r = DB::table('insight')
            ->select('id', 'slug', 'type', 'title_id', 'title_en', 'description_id', 'description_en', 'image', 'tanggal_publikasi')
            ->where('publikasi', 'publish')
            ->where('status', 'on')
            ->where(DB::raw('LOWER(type)'), 'ngopini')
            ->orderByDesc('tanggal_publikasi')
            ->orderByDesc('id')
            ->first();

        if ($r) {
            $title = $loc === 'id' ? ($r->title_id ?? $r->title_en) : ($r->title_en ?? $r->title_id);
            $descRaw = $loc === 'id' ? ($r->description_id ?? $r->description_en) : ($r->description_en ?? $r->description_id);
            $desc = strip_tags(html_entity_decode($descRaw ?? ''));
            $img = $r->image ? (Str::startsWith($r->image, ['http://', 'https://']) ? $r->image : Storage::url($r->image)) : null;

            $this->ngopini = [
                'id'    => $r->id,
                'slug'  => $r->slug,
                'title' => $title ?: 'Untitled',
                'desc'  => $desc,
                'image' => $img,
                'date'  => $r->tanggal_publikasi ? Carbon::parse($r->tanggal_publikasi)->translatedFormat('F Y') : '',
                'url'   => url(app()->getLocale() . '/opinion/' . $r->id . '/' . $r->slug),
            ];
        } else {
            $this->ngopini = null;
        }
    }

    protected function loadInfographics(): void
    {
        $loc = app()->getLocale();

        $rows = DB::table('infografik')
            ->orderByDesc('id')
            ->limit(3) // EXACT 3 buat layout
            ->get();

        $this->infographics = $rows->map(function ($item) use ($loc) {

            $images = DB::table('infografik_images')
                ->where('infografik_id', $item->id)
                ->orderBy('sort')
                ->get()
                ->map(fn($img) => Storage::url($img->image))
                ->toArray();

            return [
                'id'     => $item->id,
                'slug'   => $item->slug,
                'title'  => $loc === 'id' ? $item->title_id : $item->title_en,
                'images' => $images,
            ];
        })->toArray();
    }


    public function render()
    {
        return view('livewire.index');
    }
}
