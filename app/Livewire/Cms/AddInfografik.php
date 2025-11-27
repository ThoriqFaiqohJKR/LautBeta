<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddInfografik extends Component
{
    use WithFileUploads;

    public $title_id, $title_en;
    public $description_id, $description_en;
    public $publikasi = 'draft';
    public $tanggal_publikasi;

    public $images = [];        // final untuk disimpan
    public $newImages = [];     // file yang baru diinput

    // ✔ setiap input file berubah, gabungkan dengan gambar sebelumnya
    public function updatedNewImages($files)
    {
        foreach ($files as $file) {
            $this->images[] = $file;
        }
    }

    public function save()
    {
        $this->validate([
            'title_id' => 'required|max:255',
            'images.*' => 'image|max:2048',
        ]);

        // SIMPAN DATA UTAMA
        $id = DB::table('infografik')->insertGetId([
            'title_id' => $this->title_id,
            'title_en' => $this->title_en,
            'description_id' => $this->description_id,
            'description_en' => $this->description_en,
            'slug' => Str::slug($this->title_id ?? Str::random(8)),
            'publikasi' => $this->publikasi,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // SIMPAN SEMUA GAMBAR
        foreach ($this->images as $idx => $img) {
            $path = $img->store('infografik/gallery', 'public');

            DB::table('infografik_images')->insert([
                'infografik_id' => $id,
                'image' => $path,
                'sort' => $idx,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        session()->flash('success', 'Infografik berhasil disimpan!');
        return redirect()->route('cms.page.index.infografik', [
            'locale' => app()->getLocale()
        ]);
    }

    public function removeImage($index)
    {
        array_splice($this->images, $index, 1);
    }

    public function render()
    {
        return view('livewire.cms.add-infografik');
    }
}
