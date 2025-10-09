<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\File as FileRule;
use Illuminate\Support\Str;

class AddLiteracy extends Component
{
    use WithFileUploads;

    public string $type = 'journal';
    public string $lang = 'en';

    public string $title_en = '';
    public string $title_id = '';
    public string $description_en = '';
    public string $description_id = '';
    public string $content_en = '';
    public string $content_id = '';
    public ?string $tanggal_publikasi = null;
    public string $publikasi = 'draf';
    public string $status = 'on';

    public $image = null;
    public ?string $imagePreview = null;

    
    public ?string $file_id = null;   // link dokumen bahasa Indonesia
    public ?string $file_en = null;   // link dokumen bahasa Inggris

    protected function rules(): array
    {
        $base = [
            'type'               => ['required', 'in:journal,case_report'],
            'lang'               => ['required', 'in:en,id'],
            'title_en'           => ['required', 'string', 'max:255', 'required_without:title_id'],
            'title_id'           => ['required', 'string', 'max:255', 'required_without:title_en'],
            'description_en'     => ['required', 'string'],
            'description_id'     => ['required', 'string'],
            'tanggal_publikasi'  => ['required', 'date'],
            'publikasi'          => ['required', 'in:draf,publish'],
            'image'              => ['nullable', FileRule::image()->max(5 * 1024)],
        ];

        if ($this->type === 'journal') {
            // ✅ Minimal salah satu link harus ada
            $base['file_id'] = ['nullable', 'url', 'required_without:file_en'];
            $base['file_en'] = ['nullable', 'url', 'required_without:file_id'];
        } else {
            
            $base['content_en'] = ['required', 'string'];
            $base['content_id'] = ['required', 'string'];
        }

        return $base;
    }

    protected $messages = [
        'title_en.required_without' => 'Minimal isi salah satu judul: EN atau ID.',
        'title_id.required_without' => 'Minimal isi salah satu judul: EN atau ID.',
        'file_id.required_without'  => 'Isi minimal salah satu link: ID atau EN.',
        'file_en.required_without'  => 'Isi minimal salah satu link: ID atau EN.',
        'file_id.url'               => 'Link file (ID) harus URL yang valid.',
        'file_en.url'               => 'Link file (EN) harus URL yang valid.',
    ];

    public function updatedImage(): void
    {
        if ($this->image && method_exists($this->image, 'temporaryUrl')) {
            $this->imagePreview = $this->image->temporaryUrl();
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->type === 'journal') {
            $this->saveJournal();
        } else {
            $this->saveCaseReport();
        }

        $this->resetForm();
        session()->flash('success', 'Data berhasil disimpan.');
    }

    private function nextId(): int
    {
        $maxJournal = DB::table('journal')->max('id') ?? 0;
        $maxCase    = DB::table('case_report')->max('id') ?? 0;
        return max([$maxJournal, $maxCase]) + 1;
    }

    private function saveJournal(): void
    {
        $data = [
            'id'                => $this->nextId(),
            'title_en'          => $this->title_en ?: null,
            'title_id'          => $this->title_id ?: null,
            'description_en'    => $this->description_en ?: null,
            'description_id'    => $this->description_id ?: null,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'publikasi'         => $this->publikasi,
            'slug'              => Str::slug($this->title_id),
            'created_at'        => now(),
            'updated_at'        => now(),
            
            'file_id'           => $this->file_id,
            'file_en'           => $this->file_en,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('literacy/journal/images', 'public');
        }

        DB::table('journal')->insert($data);
    }

    private function saveCaseReport(): void
    {
        $data = [
            'id'                => $this->nextId(),
            'title_en'          => $this->title_en ?: null,
            'title_id'          => $this->title_id ?: null,
            'description_en'    => $this->description_en ?: null,
            'description_id'    => $this->description_id ?: null,
            'content_en'        => $this->content_en ?: null,
            'content_id'        => $this->content_id ?: null,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'publikasi'         => $this->publikasi,
            'status'            => 'on',
            'slug'              => Str::slug($this->title_id),
            'created_at'        => now(),
            'updated_at'        => now(),
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('literacy/case-report/images', 'public');
        }

        DB::table('case_report')->insert($data);
    }

    private function resetForm(): void
    {
        $this->title_en = $this->title_id = '';
        $this->description_en = $this->description_id = '';
        $this->content_en = $this->content_id = '';
        $this->tanggal_publikasi = null;
        $this->publikasi = 'draf';
        $this->status = 'on';
        $this->image = null;
        $this->imagePreview = null;

        // 🔹 reset link baru
        $this->file_id = null;
        $this->file_en = null;
    }

    public function render()
    {
        return view('livewire.cms.add-literacy');
    }
}
