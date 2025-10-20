<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class JournalPreview extends Component
{
    public $id;
    public $pdf_url;

    public function mount()
    {
        $this->id = request()->route('id');

        $row = DB::table('journal')
            ->select('file_en', 'file_id')
            ->where('id', $this->id)
            ->first();

        if (!$row) abort(404);

        $locale = app()->getLocale();
        $this->pdf_url = $locale === 'id'
            ? $row->file_id
            : $row->file_en;
    }

    public function render()
    {
        return view('livewire.journal-preview', [
            'pdf_url' => $this->pdf_url
        ]);
    }
}
