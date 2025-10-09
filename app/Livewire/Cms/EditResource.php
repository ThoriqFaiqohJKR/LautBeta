<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class EditResource extends Component
{
    use WithFileUploads;

    public string $section = 'report';
    public $id = null;

    public string $title_en = '';
    public string $title_id = '';
    public ?string $description = null;
    public string $status = 'on';
    public string $publikasi = 'draf';
    public ?string $tanggal_publikasi = null;

    public $report_file = null;
    public ?string $report_path = null;
    public ?string $report_url = null;

    public ?string $db_link = null;
    public ?string $db_version = null;

    public $gallery_image = null;
    public ?string $gallery_url = null;
    public ?string $gallery_category = null;

    protected function getTable(string $section): string
    {
        return match ($section) {
            'report'   => 'report',
            'database' => 'database',
            'gallery'  => 'gallery',
            default    => 'report',
        };
    }

    protected function findOne(string $table, int $id)
    {
        if (!Schema::hasTable($table)) return null;
        return DB::table($table)->where('id', $id)->first();
    }

    protected function findRowAndTable(string $preferredSection, int $id): array
    {
        $order = array_values(array_unique([$preferredSection, 'report', 'database', 'gallery']));
        foreach ($order as $sec) {
            $table = $this->getTable($sec);
            $row = $this->findOne($table, $id);
            if ($row) return ['section' => $sec, 'table' => $table, 'row' => $row];
        }
        return ['section' => null, 'table' => null, 'row' => null];
    }

    protected function fillFromRow(string $section, $row): void
    {
        if ($section === 'report') {
            $this->fill([
                'title_en' => $row->title_en ?? '',
                'title_id' => $row->title_id ?? '',
                'description' => $row->description ?? null,
                'status' => $row->status ?? 'on',
                'publikasi' => $row->publikasi ?? 'draf',
                'tanggal_publikasi' => $row->tanggal_publikasi ?? null,
                'report_path' => $row->path ?? null,
                'report_url' => $row->url ?? null,
            ]);
        } elseif ($section === 'database') {
            $this->fill([
                'title_en' => $row->title_en ?? '',
                'title_id' => $row->title_id ?? '',
                'description' => $row->description ?? null,
                'status' => $row->status ?? 'on',
                'publikasi' => $row->publikasi ?? 'draf',
                'tanggal_publikasi' => $row->tanggal_publikasi ?? null,
                'db_link' => $row->link ?? null,
                'db_version' => $row->version ?? null,
            ]);
        } else {
            $r = (array) $row;
            $this->fill([
                'title_en' => $r['title_en'] ?? '',
                'title_id' => $r['title_id'] ?? '',
                'description' => $r['description'] ?? null,
                'status' => $r['status'] ?? 'on',
                'publikasi' => $r['publikasi'] ?? 'draf',
                'tanggal_publikasi' => $r['tanggal_publikasi'] ?? null,
                'gallery_url' => $r['image_url'] ?? ($r['file_en'] ?? null),
                'gallery_category' => $r['category'] ?? null,
            ]);
        }
    }

    public function mount($section = null, $id = null): void
    {
        $section = $section ?? request()->route('section');
        $id = $id ?? request()->route('id');
        $allowed = ['report', 'database', 'gallery'];
        $this->section = in_array($section, $allowed, true) ? $section : 'report';
        $this->id = is_numeric($id) ? (int) $id : null;
        if (!$this->id) return;

        $probe = $this->findRowAndTable($this->section, $this->id);
        if (!$probe['row']) abort(404);
        $this->section = $probe['section'];
        $this->fillFromRow($this->section, $probe['row']);
    }

    protected function rulesReport(): array
    {
        return [
            'title_en' => 'required_without:title_id|max:255',
            'title_id' => 'required_without:title_en|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:on,off',
            'publikasi' => 'required|in:draf,publish',
            'tanggal_publikasi' => 'nullable|date',
            'report_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,zip|max:12288',
            'report_url' => 'nullable|url',
        ];
    }

    protected function rulesDatabase(): array
    {
        return [
            'title_en' => 'required_without:title_id|max:255',
            'title_id' => 'required_without:title_en|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:on,off',
            'publikasi' => 'required|in:draf,publish',
            'tanggal_publikasi' => 'nullable|date',
            'db_link' => 'required|url',
            'db_version' => 'required|string|max:100',
        ];
    }

    protected function rulesGallery(): array
    {
        return [
            'title_en' => 'required_without:title_id|max:255',
            'title_id' => 'required_without:title_en|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:on,off',
            'publikasi' => 'required|in:draf,publish',
            'tanggal_publikasi' => 'nullable|date',
            'gallery_image' => 'nullable|image|max:6144',
            'gallery_url' => 'nullable|url',
            'gallery_category' => 'nullable|string|max:100',
        ];
    }

    public function rules(): array
    {
        return match ($this->section) {
            'report' => $this->rulesReport(),
            'database' => $this->rulesDatabase(),
            'gallery' => $this->rulesGallery(),
            default => $this->rulesReport(),
        };
    }

    public function save(): void
    {
        match ($this->section) {
            'report' => $this->updateReport(),
            'database' => $this->updateDatabase(),
            'gallery' => $this->updateGallery(),
            default => null,
        };
    }

    public function updateReport(): void
    {
        $this->validate($this->rulesReport());
        if (!$this->id) abort(400);
        if (!$this->report_file && !$this->report_url) {
            $this->addError('report_file', 'Unggah file atau isi URL salah satu.');
            $this->addError('report_url', 'Unggah file atau isi URL salah satu.');
            return;
        }
        if ($this->report_file) {
            $this->report_path = $this->report_file->store('reports', 'public');
        }
        $updates = [
            'title_en' => $this->title_en,
            'title_id' => $this->title_id,
            'description' => $this->description,
            'publikasi' => $this->publikasi,
            'status' => $this->status,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'updated_at' => now(),
        ];
        if ($this->report_path) $updates['path'] = $this->report_path;
        if (!is_null($this->report_url)) $updates['url'] = $this->report_url;
        DB::table('report')->where('id', $this->id)->update($updates);
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Report updated']);
    }

    public function updateDatabase(): void
    {
        $this->validate($this->rulesDatabase());
        if (!$this->id) abort(400);
        $table = 'database';
        if (!Schema::hasTable($table)) abort(404);
        DB::table($table)->where('id', $this->id)->update([
            'title_en' => $this->title_en,
            'title_id' => $this->title_id,
            'description' => $this->description,
            'publikasi' => $this->publikasi,
            'status' => $this->status,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'link' => $this->db_link,
            'version' => $this->db_version,
            'updated_at' => now(),
        ]);
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Database updated']);
    }

    public function updateGallery(): void
    {
        $this->validate($this->rulesGallery());
        if (!$this->id) abort(400);
        if (!$this->gallery_image && !$this->gallery_url) {
            $this->addError('gallery_image', 'Unggah gambar atau isi URL salah satu.');
            $this->addError('gallery_url', 'Unggah gambar atau isi URL salah satu.');
            return;
        }
        if ($this->gallery_image) {
            $saved = $this->gallery_image->store('gallery', 'public');
            $this->gallery_url = Storage::url($saved);
        }
        $hasImageUrl = Schema::hasColumn('gallery', 'image_url');
        $hasFileEn = Schema::hasColumn('gallery', 'file_en');
        $hasFileId = Schema::hasColumn('gallery', 'file_id');
        $updates = [
            'title_en' => $this->title_en,
            'title_id' => $this->title_id,
            'description' => $this->description,
            'publikasi' => $this->publikasi,
            'status' => $this->status,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'updated_at' => now(),
        ];
        if ($hasImageUrl && !is_null($this->gallery_url)) $updates['image_url'] = $this->gallery_url;
        if ($hasFileEn && !is_null($this->gallery_url)) $updates['file_en'] = $this->gallery_url;
        if ($hasFileId) {}
        if (Schema::hasColumn('gallery', 'category')) $updates['category'] = $this->gallery_category;
        DB::table('gallery')->where('id', $this->id)->update($updates);
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Gallery updated']);
    }

    public function render()
    {
        return view('livewire.cms.edit-resource');
    }
}