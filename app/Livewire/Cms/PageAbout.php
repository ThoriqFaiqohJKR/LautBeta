<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PageAbout extends Component
{
    public string $lang = 'en';
    public bool $editMode = false;
    public ?int $aboutId = null;

    // Fields
    public string $titleEN = '';
    public string $contentEN = '';
    public string $titleID = '';
    public string $contentID = '';

    protected $rules = [
        'titleEN'   => 'required|string|min:3',
        'titleID'   => 'required|string|min:3',
        'contentEN' => 'required|string',
        'contentID' => 'required|string',
    ];

    public function mount(): void
    {
        // Ambil satu baris abouts (kalau multi-row, sesuaikan)
        $row = DB::table('abouts')->first();
        if ($row) {
            $this->aboutId   = (int) $row->id;
            $this->titleEN   = (string) ($row->title_en ?? '');
            $this->contentEN = (string) ($row->description_en ?? '');
            $this->titleID   = (string) ($row->title_id ?? '');
            $this->contentID = (string) ($row->description_id ?? '');
        }
    }

    /** Masuk ke mode edit */
    public function startEdit(): void
    {
        $this->editMode = true;
    }

    /** Batal edit (kembalikan nilai dari DB + kembali ke view mode) */
    public function cancelEdit(): void
    {
        $this->resetValidation();
        $this->editMode = false;

        if ($this->aboutId) {
            $row = DB::table('abouts')->where('id', $this->aboutId)->first();
            if ($row) {
                $this->titleEN   = (string) ($row->title_en ?? '');
                $this->contentEN = (string) ($row->description_en ?? '');
                $this->titleID   = (string) ($row->title_id ?? '');
                $this->contentID = (string) ($row->description_id ?? '');
            }
        } else {
            // kosongkan jika belum ada data
            $this->titleEN = $this->contentEN = $this->titleID = $this->contentID = '';
        }
    }

    /** Simpan (insert/update) lalu kembali ke view mode */
    public function save(): void
    {
        $this->validate();

        $payload = [
            'title_en'       => $this->titleEN,
            'description_en' => $this->contentEN,
            'title_id'       => $this->titleID,
            'description_id' => $this->contentID,
            'updated_at'     => now(),
        ];

        if ($this->aboutId) {
            DB::table('abouts')->where('id', $this->aboutId)->update($payload);
        } else {
            $payload['created_at'] = now();
            $this->aboutId = DB::table('abouts')->insertGetId($payload);
        }

        $this->editMode = false;
        session()->flash('success', 'Data About berhasil disimpan.');
        $this->dispatch('about-saved');
    }

    public function render()
    {
        return view('livewire.cms.page-about');
    }
}
