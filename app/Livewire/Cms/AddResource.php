<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class AddResource extends Component
{
    use WithFileUploads;

    public string $source = 'report';
    public string $lang   = 'en';

    public ?string $title_en = '';
    public ?string $title_id = '';
    public ?string $description_en = '';
    public ?string $description_id = '';
    public ?string $content_en = '';
    public ?string $content_id = '';

    public ?string $tanggal_publikasi = null;
    public string  $publikasi = 'draf';
    public string  $status = 'on';

    public $image = null;
    public ?string $imagePreview = null;

    public ?int $database_id = null;
    public string $gallery_type = 'photo';
    public ?string $file_id = null;
    public ?string $file_en = null;

    public array $databaseOptions = [];

    public function mount(): void
    {
        if ($this->source === 'gallery') {
            $this->loadDatabaseOptions();
        }
    }

    public function updatedSource(string $val): void
    {
        if ($val === 'gallery') $this->loadDatabaseOptions();
    }

    private function loadDatabaseOptions(): void
    {
        $title = app()->getLocale() === 'id' ? 'title_id' : 'title_en';
        $this->databaseOptions = DB::table('database')
            ->select('id', DB::raw("COALESCE($title, title_en, title_id) as label"))
            ->orderByDesc('id')
            ->limit(200)
            ->get()
            ->map(fn($r) => ['id' => $r->id, 'label' => ($r->label ?: ('ID ' . $r->id))])
            ->all();
    }

    private function nextId(): int
    {
        $maxReport   = DB::table('report')->max('id') ?? 0;
        $maxDatabase = DB::table('database')->max('id') ?? 0;
        $maxGallery  = DB::table('gallery')->max('id') ?? 0;
        return max([$maxReport, $maxDatabase, $maxGallery]) + 1;
    }

    public function saveReport(): void
    {
        $this->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_id' => ['required', 'string', 'max:255'],
            'description_en' => ['required', 'string'],
            'description_id' => ['required', 'string'],
            'content_en' => ['required', 'string'],
            'content_id' => ['required', 'string'],
            'tanggal_publikasi' => ['required', 'date'],
            'publikasi' => ['required', 'in:draf,publish'],
            'status' => ['required', 'in:on,off'],
            'image' => ['required', File::image()->max(51200)],
        ]);

        DB::transaction(function () {
            $id = $this->nextId();

            $data = [
                'id' => $id,
                'title_en' => $this->title_en ?: null,
                'title_id' => $this->title_id ?: null,
                'description_en' => $this->description_en ?: null,
                'description_id' => $this->description_id ?: null,
                'content_en' => $this->content_en ?: null,
                'content_id' => $this->content_id ?: null,
                'tanggal_publikasi' => $this->tanggal_publikasi,
                'publikasi' => $this->publikasi,
                'status' => $this->status,
                'slug' => Str::slug($this->title_id ?: $this->title_en),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($this->image) {
                $stored = $this->image->store('report/image', 'public');
                $data['image'] = $stored;
                $this->imagePreview = Storage::url($stored);
            }

            DB::table('report')->insert($data);
        });

        $this->resetForm();
        session()->flash('success', 'Report berhasil disimpan.');
    }

    public function saveDatabase(): void
    {
        $this->validate([
            'title_en' => ['required', 'string', 'max:255'],
            'title_id' => ['required', 'string', 'max:255'],
            'description_en' => ['required', 'string'],
            'description_id' => ['required', 'string'],
            'content_en' => ['required', 'string'],
            'content_id' => ['required', 'string'],
            'tanggal_publikasi' => ['required', 'date'],
            'publikasi' => ['required', 'in:draf,publish'],
            'status' => ['required', 'in:on,off'],
            'image' => ['required', File::image()->max(51200)],
        ]);

        DB::transaction(function () {
            $id = $this->nextId();

            $data = [
                'id' => $id,
                'title_en' => $this->title_en ?: null,
                'title_id' => $this->title_id ?: null,
                'description_en' => $this->description_en ?: null,
                'description_id' => $this->description_id ?: null,
                'content_en' => $this->content_en ?: null,
                'content_id' => $this->content_id ?: null,
                'tanggal_publikasi' => $this->tanggal_publikasi,
                'publikasi' => $this->publikasi,
                'status' => $this->status,
                'slug' => Str::slug($this->title_id ?: $this->title_en),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($this->image) {
                $stored = $this->image->store('database/image', 'public');
                $data['image'] = $stored;
                $this->imagePreview = Storage::url($stored);
            }

            DB::table('database')->insert($data);
        });

        $this->resetForm();
        session()->flash('success', 'Database berhasil disimpan.');
    }

    public function saveGallery(): void
    {
        $this->validate([
            'title_en'          => ['required', 'string', 'max:255'],
            'title_id'          => ['required', 'string', 'max:255'],
            'tanggal_publikasi' => ['required', 'date'],
            'publikasi'         => ['required', 'in:draf,publish'],
            'status'            => ['required', 'in:on,off'],
            'gallery_type'      => ['required', 'in:photo,video'],
            'database_id'       => ['required', 'integer', 'exists:database,id'],
            'file_id'           => ['nullable', 'url', 'required_without:file_en'],
            'file_en'           => ['nullable', 'url', 'required_without:file_id'],
        ]);

        DB::transaction(function () {
            $id = $this->nextId();

            $data = [
                'id'                 => $id,
                'title_en'           => $this->title_en ?: '',
                'title_id'           => $this->title_id ?: '',
                'description_en'     => $this->description_en ?: '',
                'description_id'     => $this->description_id ?: '',
                'content_en'         => $this->content_en ?: '',
                'content_id'         => $this->content_id ?: '',
                'tanggal_publikasi'  => $this->tanggal_publikasi,
                'publikasi'          => $this->publikasi,
                'status'             => $this->status,
                'slug'               => Str::slug($this->title_id ?: $this->title_en),
                'type'               => $this->gallery_type,
                'database_id'        => $this->database_id,
                'file_id'            => $this->file_id,
                'file_en'            => $this->file_en,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];

            DB::table('gallery')->insert($data);
        });

        $this->resetForm();
        session()->flash('success', 'Gallery berhasil disimpan.');
    }

    public function save(): void
    {
        if ($this->source === 'gallery') {
            $this->saveGallery();
            return;
        }
        if ($this->source === 'database') {
            $this->saveDatabase();
            return;
        }
        $this->saveReport();
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
        $this->database_id = null;
        $this->gallery_type = 'photo';
        $this->file_id = null;
        $this->file_en = null;
        $this->databaseOptions = [];
        $this->source = 'report';
        $this->lang = 'en';
    }

    public function render()
    {
        return view('livewire.cms.add-resource');
    }
}
