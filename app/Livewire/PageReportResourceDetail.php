<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageReportResourceDetail extends Component
{
    public array $item = [];

    public function mount(): void
    {
        $id      = (int) request()->route('id');
        $urlSlug = request()->route('slug');
        $locale  = app()->getLocale();

        $row = DB::table('report')
            ->select([
                'id',
                'title_id',
                'title_en',
                'description_id',
                'description_en',
                'content_id',
                'content_en',
                'image',
                'tanggal_publikasi',
                'slug',
            ])
            ->where('id', $id)
            ->first();

        if (!$row) abort(404, 'Data tidak ditemukan');

        // pilih konten sesuai locale (+fallback)
        $title = $locale === 'id'
            ? ($row->title_id ?? $row->title_en)
            : ($row->title_en ?? $row->title_id);

        $desc = $locale === 'id'
            ? ($row->description_id ?? $row->description_en)
            : ($row->description_en ?? $row->description_id);

        $cont = $locale === 'id'
            ? ($row->content_id ?? $row->content_en)
            : ($row->content_en ?? $row->content_id);

        // slug kanonis
        $canonicalSlug = $row->slug ?: Str::slug((string)$title);
        $this->item = [
            'id'          => $row->id,
            'slug'        => $canonicalSlug,
            'title'       => $title ?: 'Untitled',
            'description' => $this->fixContentImages((string)$desc),
            'content'     => $this->fixContentImages((string)$cont),
            'image'       => $row->image ? Storage::url($row->image) : null,
            'tanggal_publikasi' => $row->tanggal_publikasi,
        ];
    }

    public function render()
    {
        return view('livewire.page-report-resource-detail');
    }
}
