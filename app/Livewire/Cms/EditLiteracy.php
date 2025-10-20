<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File as FileRule;

class EditLiteracy extends Component
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
    public ?string $existing_image = null;

    // Ganti single $file -> dua file terpisah
    public $file_en = null;
    public $file_id = null;
    public ?string $existing_file_en = null;
    public ?string $existing_file_id = null;

    public int $literacyId;

    public function mount(int $id): void
    {
        $this->literacyId = $id;

        $forced = request()->query('t');
        if ($forced === 'journal' || $forced === 'case_report') {
            $this->type = $forced;
            $row = DB::table($this->type)->find($id);
        } else {
            $row = DB::table('journal')->find($id);
            if ($row) {
                $this->type = 'journal';
            } else {
                $row = DB::table('case_report')->find($id);
                if ($row) $this->type = 'case_report';
            }
        }

        if (!$row) abort(404);

        $this->title_en          = $row->title_en ?? '';
        $this->title_id          = $row->title_id ?? '';
        $this->description_en    = $row->description_en ?? '';
        $this->description_id    = $row->description_id ?? '';
        $this->content_en        = $row->content_en ?? '';
        $this->content_id        = $row->content_id ?? '';
        $this->tanggal_publikasi = $row->tanggal_publikasi ?? null;
        $this->publikasi         = $row->publikasi ?? 'draf';
        $this->status            = $row->status ?? 'on';

        $this->existing_image    = $row->image ?? null;
        $this->imagePreview      = $this->existing_image ? Storage::url($this->existing_image) : null;

        // ambil file lama per bahasa (untuk journal)
        $this->existing_file_en  = $row->file_en ?? null;
        $this->existing_file_id  = $row->file_id ?? null;
    }

    protected function rules(): array
    {
        $base = [
            'title_en'          => ['required_without:title_id', 'string', 'max:255'],
            'title_id'          => ['required_without:title_en', 'string', 'max:255'],
            'description_en'    => ['required', 'string'],
            'description_id'    => ['required', 'string'],
            'tanggal_publikasi' => ['required', 'date'],
            'publikasi'         => ['required', 'in:draf,publish'],
            'image'             => ['nullable', FileRule::image()->max(5 * 1024)],
        ];

        if ($this->type === 'journal') {
            $base['file_en'] = ['nullable', FileRule::types(['pdf', 'doc', 'docx', 'ppt', 'pptx'])->max(20 * 1024)];
            $base['file_id'] = ['nullable', FileRule::types(['pdf', 'doc', 'docx', 'ppt', 'pptx'])->max(20 * 1024)];
        } else {
            $base['status']     = ['required', 'in:on,off'];
            $base['content_en'] = ['required', 'string'];
            $base['content_id'] = ['required', 'string'];
        }

        return $base;
    }

    public function updatedImage(): void
    {
        if ($this->image && method_exists($this->image, 'temporaryUrl')) {
            $this->imagePreview = $this->image->temporaryUrl();
        }
    }

    public function update(): void
    {
        $this->validate();

        if ($this->type === 'journal') {
            // minimal salah satu EN/ID harus ada (baru atau existing)
            $hasAny = ($this->file_en || $this->existing_file_en || $this->file_id || $this->existing_file_id);
            if (!$hasAny) {
                $this->addError('file_en', 'Minimal unggah salah satu file (EN/ID) atau biarkan file lama.');
                $this->addError('file_id', 'Minimal unggah salah satu file (EN/ID) atau biarkan file lama.');
                return;
            }
            $this->updateJournal();
        } else {
            $this->updateCaseReport();
        }

        session()->flash('success', 'Data berhasil diperbarui.');
    }

    private function updateJournal(): void
    {
        $data = [
            'title_en'          => $this->title_en ?: null,
            'title_id'          => $this->title_id ?: null,
            'description_en'    => $this->description_en ?: null,
            'description_id'    => $this->description_id ?: null,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'publikasi'         => $this->publikasi,
            'updated_at'        => now(),
        ];

        if ($this->image) {
            $path = $this->image->store('literacy/journal/images', 'public');
            $data['image'] = $path;
            $this->existing_image = $path;
            $this->imagePreview = Storage::url($path);
        } elseif ($this->existing_image) {
            $data['image'] = $this->existing_image;
        }

        // file EN (baru -> simpan; kalau tidak, pakai existing)
        if ($this->file_en) {
            $pathEn = $this->file_en->store('literacy/journal/files/en', 'public');
            $data['file_en'] = Storage::url($pathEn);
            $this->existing_file_en = $pathEn;
        } elseif ($this->existing_file_en) {
            $data['file_en'] = $this->existing_file_en;
        }

        // file ID
        if ($this->file_id) {
            $pathId = $this->file_id->store('literacy/journal/files/id', 'public');
            $data['file_id'] = Storage::url($pathId);
            $this->existing_file_id = $pathId;
        } elseif ($this->existing_file_id) {
            $data['file_id'] = $this->existing_file_id;
        }

        DB::table('journal')->where('id', $this->literacyId)->update($data);
    }

    private function updateCaseReport(): void
    {
        $data = [
            'title_en'          => $this->title_en ?: null,
            'title_id'          => $this->title_id ?: null,
            'description_en'    => $this->description_en ?: null,
            'description_id'    => $this->description_id ?: null,
            'content_en'        => $this->content_en ?: null,
            'content_id'        => $this->content_id ?: null,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'publikasi'         => $this->publikasi,
            'status'            => $this->status,
            'updated_at'        => now(),
        ];

        if ($this->image) {
            $path = $this->image->store('literacy/case-report/images', 'public');
            $data['image'] = $path;
            $this->existing_image = $path;
            $this->imagePreview = Storage::url($path);
        } elseif ($this->existing_image) {
            $data['image'] = $this->existing_image;
        }

        DB::table('case_report')->where('id', $this->literacyId)->update($data);
    }

    public function render()
    {
        return view('livewire.cms.edit-literacy');
    }
}
