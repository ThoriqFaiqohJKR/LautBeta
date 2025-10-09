<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageInsightDetail extends Component
{
    public array $item = [];

    public function mount(): void
    {
        $id      = (int) request()->route('id');
        $urlSlug = request()->route('slug');
        $locale  = app()->getLocale();

        $row = DB::table('insight')
            ->select('id', 'type', 'title_id', 'title_en', 'description_id', 'description_en', 'content_id', 'content_en', 'image', 'tanggal_publikasi', 'slug', 'publikasi', 'status')
            ->where('id', $id)
            ->where('publikasi', 'publish')
            ->where('status', 'on')
            ->whereIn(DB::raw('LOWER(type)'), ['feature', 'analysis'])
            ->first();

        if (!$row) abort(404, 'Data tidak ditemukan');

        $title = $locale === 'id'
            ? ($row->title_id ?? $row->title_en)
            : ($row->title_en ?? $row->title_id);

        $desc = $locale === 'id'
            ? ($row->description_id ?? $row->description_en)
            : ($row->description_en ?? $row->description_id);

        $cont = $locale === 'id'
            ? ($row->content_id ?? $row->content_en)
            : ($row->content_en ?? $row->content_id);

        $slug = $row->slug ?: Str::slug((string)$title);

        $this->item = [
            'id'          => $row->id,
            'slug'        => $slug,
            'type'        => strtoupper($row->type),
            'title'       => $title ?: 'Untitled',
            'description' => $this->fixContentImages((string)$desc),
            'content'     => $this->fixContentImages((string)$cont),
            'image_url'   => $this->imageUrl($row->image),
            'tanggal'     => $row->tanggal_publikasi,
        ];
    }

    private function fixContentImages(string $html): string
    {
        if ($html === '') return $html;

        return preg_replace_callback(
            '~(<img\s[^>]*src=["\'])([^"\']+)(["\'])~i',
            function ($m) {
                $prefix = $m[1];
                $src    = $m[2];
                $suffix = $m[3];
                if (preg_match('~^https?://~i', $src)) return $m[0];
                $normalized = ltrim($src, './');
                while (str_starts_with($normalized, '../')) $normalized = substr($normalized, 3);
                $normalized = ltrim($normalized, '/');
                if (str_starts_with($normalized, 'storage/')) return $prefix . asset($normalized) . $suffix;
                if (Storage::disk('public')->exists($normalized)) return $prefix . Storage::url($normalized) . $suffix;
                return $prefix . asset($normalized) . $suffix;
            },
            $html
        );
    }

    private function imageUrl(?string $path): string
    {
        if (!$path) return asset('img/placeholder-16x9.png');
        if (preg_match('~^https?://~i', $path)) return $path;
        if (str_starts_with($path, 'storage/')) return asset($path);
        if (Storage::disk('public')->exists($path)) return Storage::url($path);
        return asset(ltrim($path, '/'));
    }

    public function render()
    {
        return view('livewire.page-insight-detail');
    }
}
