<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\File as FileRule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddLiteracy extends Component
{
    use WithFileUploads;

    public string $lang = 'en';

    public string $title_en = '';
    public string $title_id = '';
    public string $description_en = '';
    public string $description_id = '';
    public ?string $tanggal_publikasi = null;
    public string $publikasi = 'draf';
    public string $status = 'on';

    public $image = null;
    public ?string $imagePreview = null;

    public $file_en_upload = null;
    public $file_id_upload = null;

    protected function rules(): array
    {
        return [
            'lang'              => ['required', 'in:en,id'],
            'title_en'          => ['required_without:title_id', 'string', 'max:255'],
            'title_id'          => ['required_without:title_en', 'string', 'max:255'],
            'description_en'    => ['required', 'string'],
            'description_id'    => ['required', 'string'],
            'tanggal_publikasi' => ['required', 'date'],
            'publikasi'         => ['required', 'in:draf,publish'],
            'status'            => ['required', 'in:on,off'],

            'image'             => ['required', FileRule::image()->max(5 * 1024)],

            'file_en_upload'    => ['nullable', FileRule::types(['pdf', 'doc', 'docx', 'ppt', 'pptx'])->max(20 * 1024)],
            'file_id_upload'    => ['nullable', FileRule::types(['pdf', 'doc', 'docx', 'ppt', 'pptx'])->max(20 * 1024)],
        ];
    }

    public function updatedImage(): void
    {
        if ($this->image && method_exists($this->image, 'temporaryUrl')) {
            $this->imagePreview = $this->image->temporaryUrl();
        }
    }

    public function save()
    {
        $this->validate();

        if (!$this->file_en_upload && !$this->file_id_upload) {
            $this->addError('file_en_upload', 'Upload minimal salah satu file (EN atau ID).');
            $this->addError('file_id_upload', 'Upload minimal salah satu file (EN atau ID).');
            return;
        }

        $data = [
            'title_en'          => $this->title_en ?: null,
            'title_id'          => $this->title_id ?: null,
            'description_en'    => $this->description_en ?: null,
            'description_id'    => $this->description_id ?: null,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'publikasi'         => $this->publikasi,
            'status'            => $this->status,
            'slug'              => Str::slug($this->title_id ?: $this->title_en),
            'created_at'        => now(),
            'updated_at'        => now(),
        ];

        // Simpan image (required)
        $data['image'] = $this->image->store('literacy/journal/images', 'public');

        // Simpan file EN / ID
        if ($this->file_en_upload) {
            $path = $this->file_en_upload->store('literacy/journal/files/en', 'public');
            $data['file_en'] = Storage::url($path);
        }

        if ($this->file_id_upload) {
            $path = $this->file_id_upload->store('literacy/journal/files/id', 'public');
            $data['file_id'] = Storage::url($path);
        }

        DB::table('journal')->insert($data);

        return redirect()
            ->route('cms.page.index.literacy', ['locale' => app()->getLocale()])
            ->with('success', 'Jurnal berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.cms.add-literacy');
    }
}
