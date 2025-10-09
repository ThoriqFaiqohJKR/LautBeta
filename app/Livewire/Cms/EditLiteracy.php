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

    // Ditentukan otomatis di mount
    public string $type = 'journal'; // journal | case_report
    public string $lang = 'en';      // en | id

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
    public $file  = null;
    public ?string $imagePreview = null;

    public int $literacyId;

    public function mount(int $id): void
    {
        $this->literacyId = $id;

        // Optional: ?t=journal atau ?t=case_report untuk paksa pilih tabel
        $forced = request()->query('t');
        if ($forced === 'journal' || $forced === 'case_report') {
            $this->type = $forced;
            $row = DB::table($this->type)->find($id);
        } else {
            // Auto-resolve: cek journal dulu, kalau gak ada cek case_report
            $row = DB::table('journal')->find($id);
            if ($row) {
                $this->type = 'journal';
            } else {
                $row = DB::table('case_report')->find($id);
                if ($row) {
                    $this->type = 'case_report';
                }
            }
        }

        if (!$row) {
            abort(404);
        }

        $this->title_en          = $row->title_en ?? '';
        $this->title_id          = $row->title_id ?? '';
        $this->description_en    = $row->description_en ?? '';
        $this->description_id    = $row->description_id ?? '';
        $this->content_en        = $row->content_en ?? '';
        $this->content_id        = $row->content_id ?? '';
        $this->tanggal_publikasi = $row->tanggal_publikasi ?? null;
        $this->publikasi         = $row->publikasi ?? 'draf';
        $this->status            = $row->status ?? 'on';
        $this->imagePreview      = !empty($row->image) ? Storage::url($row->image) : null;
    }

    protected function rules(): array
    {
        $base = [
            'title_en'          => ['required', 'string', 'max:255', 'required_without:title_id'],
            'title_id'          => ['required', 'string', 'max:255', 'required_without:title_en'],
            'description_en'    => ['required', 'string'],
            'description_id'    => ['required', 'string'],
            'tanggal_publikasi' => ['required', 'date'],
            'publikasi'         => ['required', 'in:draf,publish'],
            'image'             => ['required', FileRule::image()->max(5 * 1024)],
        ];

        if ($this->type === 'journal') {
            $base['file'] = ['required', FileRule::types(['pdf', 'doc', 'docx', 'ppt', 'pptx'])->max(20 * 1024)];
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
        }
        if ($this->file) {
            $path = $this->file->store('literacy/journal/files', 'public');
            $data['file'] = $path;
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
        }

        DB::table('case_report')->where('id', $this->literacyId)->update($data);
    }

    public function render()
    {
        return view('livewire.cms.edit-literacy');
    }
}
