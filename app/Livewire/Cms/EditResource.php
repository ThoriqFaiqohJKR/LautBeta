<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class EditResource extends Component
{
    use WithFileUploads;

    public $lang = 'en';
    public $section = 'report';
    public $id;

    public $yt_id = null;
    public $yt_en = null;
    public $title_en = '';
    public $title_id = '';
    public $description_en = '';
    public $description_id = '';
    public $content_en = '';
    public $content_id = '';


    public $tanggal_publikasi = null;
    public $publikasi = 'draf';
    public $status = 'on';


    public $image = null;
    public $image_path = null;
    public $imagePreview = null;


    public $file_en = null;
    public $file_id = null;
    public $type = null;
    public $database_id = null;
    public $database_options = [];
    public $slug = null;


    public $file_en_preview = null;
    public $file_id_preview = null;



  
    /* --------------------- helpers --------------------- */
    protected function findOne($table, $id)
    {
        if (!Schema::hasTable($table)) return null;
        return DB::table($table)->where('id', $id)->first();
    }

    protected function storeImageIfAny($dir)
    {
        if ($this->image) return $this->image->store(trim($dir, '/'), 'public');
        return $this->image_path; // keep old image
    }
    public function updatedFileId($v)
    {
        $this->yt_id = $this->extractYoutubeId($v);
    }
    public function updatedFileEn($v)
    {
        $this->yt_en = $this->extractYoutubeId($v);
    }

    private function extractYoutubeId($url)
    {
        if (!$url) return null;
        if (preg_match('~(?:[?&]v=|youtu\.be/|/embed/|/shorts/)([A-Za-z0-9_-]{6,})~', $url, $m)) return $m[1];
        return null;
    }

    public function updatedType()
    {
        $this->resetValidation(['file_id', 'file_en']);
        $this->file_id = $this->file_en = $this->yt_id = $this->yt_en = null;
    }

    protected function refreshImagePreview()
    {
        $this->imagePreview = $this->image_path ? Storage::url($this->image_path) : null;
    }

    protected function refreshGalleryPreviews()
    {
        // Helper to convert storage path or absolute URL to a browser URL
        $toUrl = function ($val) {
            if (!$val) return null;
            // if it's already an http(s) URL, return as is
            if (is_string($val) && (str_starts_with($val, 'http://') || str_starts_with($val, 'https://'))) {
                return $val;
            }
            // otherwise treat as storage path
            return Storage::url($val);
        };

        $en = $toUrl($this->file_en);
        $id = $toUrl($this->file_id);

        // Untuk type photo, biasanya hanya simpan di file_id.
        // Biar preview muncul di kedua bahasa, fallback saling isi.
        if ($this->type === 'photo') {
            if (!$en && $id) $en = $id;
            if (!$id && $en) $id = $en;
        }

        $this->file_en_preview = $en;
        $this->file_id_preview = $id;
    }

    /* --------------------- lifecycle --------------------- */
    public function mount($section = null, $id = null)
    {
        $this->id = $id ?? request()->route('id') ?? request('id');
        $sec = $section ?? request()->route('section') ?? request('section');

        if (!$sec) {
            if ($this->id && $this->findOne('gallery', $this->id)) $sec = 'gallery';
            elseif ($this->id && $this->findOne('database', $this->id)) $sec = 'database';
            else $sec = 'report';
        }

        if ($sec === 'gallery') {
            $ok = $this->mountGallery($this->id);
            if ($ok === false && $this->id && $this->findOne('database', $this->id)) $this->mountDatabase($this->id);
            elseif ($ok === false && $this->id && $this->findOne('report', $this->id)) $this->mountReport($this->id);
        } elseif ($sec === 'database') {
            $ok = $this->mountDatabase($this->id);
            if ($ok === false && $this->id && $this->findOne('report', $this->id)) $this->mountReport($this->id);
            elseif ($ok === false && $this->id && $this->findOne('gallery', $this->id)) $this->mountGallery($this->id);
        } else {
            $ok = $this->mountReport($this->id);
            if ($ok === false && $this->id && $this->findOne('database', $this->id)) $this->mountDatabase($this->id);
            elseif ($ok === false && $this->id && $this->findOne('gallery', $this->id)) $this->mountGallery($this->id);
        }
    }

    public function mountReport($id = null)
    {
        $this->section = 'report';
        $this->id = $id;
        if (!$this->id) return false;
        $row = $this->findOne('report', $this->id);
        if (!$row) return false;
        $this->fillFromRow((array)$row);
        return true;
    }

    public function mountDatabase($id = null)
    {
        $this->section = 'database';
        $this->id = $id;
        if (!$this->id) return false;
        $row = $this->findOne('database', $this->id);
        if (!$row) return false;
        $this->fillFromRow((array)$row);
        return true;
    }

    public function mountGallery($id = null)
    {
        $this->section = 'gallery';
        $this->id = $id;
        if (!$this->id) return false;
        $row = $this->findOne('gallery', $this->id);
        if (!$row) return false;
        $data = (array)$row;
        $this->title_en = $data['title_en'] ?? ($data['tittle_en'] ?? '');
        $this->title_id = $data['title_id'] ?? '';
        $this->description_en = $data['description_en'] ?? '';
        $this->description_id = $data['description_id'] ?? '';
        $this->content_en = $data['content_en'] ?? '';
        $this->content_id = $data['content_id'] ?? '';
        $this->tanggal_publikasi = $data['tanggal_publikasi'] ?? null;
        $this->publikasi = $data['publikasi'] ?? 'draf';
        $this->status = $data['status'] ?? 'on';
        $this->file_en = $data['file_en'] ?? null;
        $this->file_id = $data['file_id'] ?? null;
        $this->type = $data['type'] ?? null; // photo|video
        $this->slug = $data['slug'] ?? $this->slug;
        $this->database_id = $data['database_id'] ?? null;
        $this->database_options = DB::table('database')
            ->select('id', 'title_en', 'title_id')
            ->orderByDesc('id')
            ->get()
            ->toArray();

        $this->refreshGalleryPreviews();
        return true;
    }

    protected function fillFromRow($row)
    {
        $this->title_en = $row['title_en'] ?? '';
        $this->title_id = $row['title_id'] ?? '';
        $this->description_en = $row['description_en'] ?? '';
        $this->description_id = $row['description_id'] ?? '';
        $this->content_en = $row['content_en'] ?? '';
        $this->content_id = $row['content_id'] ?? '';
        $this->tanggal_publikasi = $row['tanggal_publikasi'] ?? null;
        $this->publikasi = $row['publikasi'] ?? 'draf';
        $this->status = $row['status'] ?? 'on';
        $this->image_path = $row['image'] ?? null;
        $this->refreshImagePreview();
    }

    /* --------------------- validation --------------------- */
    protected function rules()
    {
        return [
            'title_en' => ['required_without:title_id', 'string', 'max:255'],
            'title_id' => ['required_without:title_en', 'string', 'max:255'],
            'description_en' => ['required', 'string'],
            'description_id' => ['required', 'string'],
            'content_en' => ['required', 'string'],
            'content_id' => ['required', 'string'],
            'tanggal_publikasi' => ['required', 'date'],
            'publikasi' => ['required', 'in:draf,publish'],
            'status' => ['required', 'in:on,off'],
            'image' => ['nullable', 'image', 'max:6144'],
        ];
    }

    /* --------------------- actions --------------------- */
    public function updateReport()
    {
        $this->section = 'report';
        $this->validate($this->rules());
        if (!$this->id) return;

        $this->image_path = $this->storeImageIfAny('resources/report/images');
        $this->refreshImagePreview();

        $updates = [
            'title_en' => $this->title_en,
            'title_id' => $this->title_id,
            'description_en' => $this->description_en,
            'description_id' => $this->description_id,
            'content_en' => $this->content_en,
            'content_id' => $this->content_id,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'publikasi' => $this->publikasi,
            'status' => $this->status,
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('report', 'image')) $updates['image'] = $this->image_path;

        DB::table('report')->where('id', $this->id)->update($updates);
        session()->flash('success', 'Report updated');
    }

    public function updateDatabase()
    {
        $this->section = 'database';
        $this->validate($this->rules());
        if (!$this->id) return;

        $this->image_path = $this->storeImageIfAny('resources/database/images');
        $this->refreshImagePreview();

        $updates = [
            'title_en' => $this->title_en,
            'title_id' => $this->title_id,
            'description_en' => $this->description_en,
            'description_id' => $this->description_id,
            'content_en' => $this->content_en,
            'content_id' => $this->content_id,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'publikasi' => $this->publikasi,
            'status' => $this->status,
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('database', 'image')) $updates['image'] = $this->image_path;

        DB::table('database')->where('id', $this->id)->update($updates);
        session()->flash('success', 'Database updated');
    }

    public function save()
    {
        if ($this->section === 'gallery') {
            $this->saveGallery();
            return;
        }

        if ($this->section === 'database') {
            $this->updateDatabase();
            return;
        }

        $this->updateReport();
    }

    private function saveGallery()
    {
        $this->validate([
            'title_id'          => 'nullable|string',
            'title_en'          => 'nullable|string',
            'description_id'    => 'nullable|string',
            'description_en'    => 'nullable|string',
            'type'              => 'required|in:photo,video',
            'status'            => 'required|in:on,off',
            'publikasi'         => 'required|in:draf,publish',
            'tanggal_publikasi' => 'nullable|date',
        ]);

        if ($this->type === 'video') {
            $this->validate([
                'file_id' => 'nullable|url',
                'file_en' => 'nullable|url',
            ]);
        }

        try {
            DB::beginTransaction();

            $now = now();
            $payload = [
                'database_id'       => $this->database_id,
                'title_id'          => $this->title_id,
                'title_en'          => $this->title_en,
                'description_id'    => $this->description_id,
                'description_en'    => $this->description_en,
                'type'              => $this->type,
                'tanggal_publikasi' => $this->tanggal_publikasi,
                'publikasi'         => $this->publikasi,
                'status'            => $this->status,
                'updated_at'        => $now,
            ];

            if ($this->type === 'photo') {
                if ($this->isUpload($this->file_id)) $this->file_id = $this->file_id->store('gallery', 'public');
                if ($this->isUpload($this->file_en)) $this->file_en = $this->file_en->store('gallery', 'public');
                if ($this->isUpload($this->image) && empty($this->file_id)) $this->file_id = $this->image->store('gallery', 'public');

                $payload['file_id'] = $this->file_id ?: null;
                $payload['file_en'] = $this->file_en ?: null;
            } else {
                $payload['file_id'] = $this->file_id;
                $payload['file_en'] = $this->file_en;
            }

            if ($this->id) {
                $affected = DB::table('gallery')->where('id', $this->id)->update($payload);
                if ($affected === 0) {
                    $payload['created_at'] = $now;
                    $this->id = DB::table('gallery')->insertGetId($payload);
                }
            } else {
                $payload['created_at'] = $now;
                $this->id = DB::table('gallery')->insertGetId($payload);
            }

            DB::commit();

            $this->file_id_preview = $this->toPreviewUrl($this->file_id);
            $this->file_en_preview = $this->toPreviewUrl($this->file_en);
            $this->reset(['image']);
            $this->refreshGalleryPreviews();
            session()->flash('success', 'Gallery berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan gallery: ' . $e->getMessage());
        }
    }

    private function isUpload($val): bool
    {
        return is_object($val) && (method_exists($val, 'store') || method_exists($val, 'temporaryUrl'));
    }

    private function toPreviewUrl($val): ?string
    {
        if (empty($val)) return null;
        if (is_string($val) && (str_starts_with($val, 'http://') || str_starts_with($val, 'https://'))) return $val;
        if (is_string($val)) return Storage::url($val);
        if ($this->isUpload($val) && method_exists($val, 'temporaryUrl')) return $val->temporaryUrl();
        return null;
    }




    public function render()
    {
        return view('livewire.cms.edit-resource');
    }
}
