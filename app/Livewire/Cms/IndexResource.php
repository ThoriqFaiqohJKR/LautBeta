<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class IndexResource extends Component
{
    public string $publikasi = 'all';
    public string $sort = 'latest';
    public string $q = '';

    public string $databasePublikasi = 'all';
    public string $databaseSort = 'latest';
    public string $databaseSearch = '';

    public string $galleryPublikasi = 'all';
    public string $gallerySort = 'latest';
    public string $galleryType = 'all';
    public string $galleryQ = '';

    protected $queryString = [
        'publikasi'         => ['except' => 'all'],
        'sort'              => ['except' => 'latest'],
        'q'                 => ['except' => ''],
        'databasePublikasi' => ['except' => 'all'],
        'databaseSort'      => ['except' => 'latest'],
        'databaseSearch'    => ['except' => ''],
        'galleryPublikasi'  => ['except' => 'all'],
        'gallerySort'       => ['except' => 'latest'],
        'galleryType'       => ['except' => 'all'],
        // galleryQ sengaja tidak dimasukkan agar tidak berat saat ngetik
    ];

    private function localeCols(): array
    {
        $title = app()->getLocale() === 'id' ? 'title_id' : 'title_en';
        $desc  = app()->getLocale() === 'id' ? 'description_id' : 'description_en';
        return [$title, $desc];
    }

    private function applySort($q, string $sort)
    {
        return match ($sort) {
            'oldest' => $q->orderBy('tanggal_publikasi')->orderBy('id'),
            'az'     => $q->orderBy('title')->orderByDesc('id'),
            'za'     => $q->orderBy('title', 'desc')->orderByDesc('id'),
            default  => $q->orderByDesc('tanggal_publikasi')->orderByDesc('id'),
        };
    }

    public function render()
    {
        [$title, $desc] = $this->localeCols();

        $escape = fn(string $s) => '%' . str_replace(['%', '_'], ['\\%', '\\_'], trim($s)) . '%';

        $reportQ = DB::table('report')
            ->select(
                'id',
                'slug',
                'tanggal_publikasi',
                'publikasi',
                DB::raw("$title as title"),
                DB::raw("$desc as description")
            )
            ->when($this->publikasi !== 'all', fn($q) => $q->where('publikasi', $this->publikasi))
            ->when($this->q !== '', function ($q) use ($title, $desc, $escape) {
                $s = $escape($this->q);
                $q->where(fn($w) => $w
                    ->where($title, 'like', $s)
                    ->orWhere($desc, 'like', $s)
                    ->orWhere('slug', 'like', $s));
            });

        $databaseQ = DB::table('database')
            ->select('id', 'slug', 'tanggal_publikasi', 'publikasi', DB::raw("$title as title"))
            ->when($this->databasePublikasi !== 'all', fn($q) => $q->where('publikasi', $this->databasePublikasi))
            ->when($this->databaseSearch !== '', function ($q) use ($title, $escape) {
                $s = $escape($this->databaseSearch);
                $q->where(fn($w) => $w
                    ->where($title, 'like', $s)
                    ->orWhere('slug', 'like', $s));
            });

        $galleryQ = DB::table('gallery')
            ->select('id', 'slug', 'tanggal_publikasi', 'publikasi', 'type', DB::raw("$title as title"))
            ->when($this->galleryPublikasi !== 'all', fn($q) => $q->where('publikasi', $this->galleryPublikasi))
            ->when($this->galleryType !== 'all', fn($q) => $q->where('type', $this->galleryType))
            ->when($this->galleryQ !== '', function ($q) use ($title, $escape) {
                $s = $escape($this->galleryQ);
                $q->where(fn($w) => $w
                    ->where($title, 'like', $s)
                    ->orWhere('slug', 'like', $s)
                    ->orWhere('type', 'like', $s));
            });

        $reports   = $this->applySort($reportQ, $this->sort)->limit(50)->get();
        $databases = $this->applySort($databaseQ, $this->databaseSort)->limit(50)->get();
        $galleries = $this->applySort($galleryQ, $this->gallerySort)->limit(50)->get();

        return view('livewire.cms.index-resource', compact('reports', 'databases', 'galleries'));
    }
}
