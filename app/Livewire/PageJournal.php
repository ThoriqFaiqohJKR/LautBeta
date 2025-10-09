<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PageJournal extends Component
{
    public array $journals = [];

    public function mount(): void
    {
        $loc = app()->getLocale(); // 'id' atau 'en'
        $rows = DB::table('journal')
            ->select([
                'id',
                'title_en',
                'title_id',
                'description_en',
                'description_id',
                'slug',
                'tanggal_publikasi',
                'image',      
                'file',       
                'publikasi',
            ])
            ->where('publikasi', 'publish')
            ->orderByDesc('tanggal_publikasi')
            ->get();

        $this->journals = $rows->map(function ($r) use ($loc) {
            $title = $loc === 'id'
                ? ($r->title_id ?: $r->title_en)
                : ($r->title_en ?: $r->title_id);

            $desc  = $loc === 'id'
                ? ($r->description_id ?: $r->description_en)
                : ($r->description_en ?: $r->description_id);

            return [
                'id' => $r->id,
                'slug' => $r->slug,
                'title' => $title,
                'description' => $desc, // bisa HTML; di-view pakai {!! !!}
                'tanggal_publikasi' => $r->tanggal_publikasi,
                'image_url' => !empty($r->image) ? Storage::url($r->image) : null,
                'file_url'  => !empty($r->file)  ? Storage::url($r->file)  : null,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.page-journal', ['journals' => $this->journals]);
    }
}
