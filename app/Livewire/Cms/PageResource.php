<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PageResource extends Component
{
    private const TBL_REPORTS   = 'reports';
    private const TBL_DATABASES = 'databases';
    private const TBL_GALLERYS  = 'gallerys';

    public string $section = 'report';
    public string $lang = 'en';

    public bool $editMode = false;
    public ?string $editingSection = null;
    public ?int $editingId = null;

    public array $report = [
        'titleEN' => '',
        'titleID' => '',
        'descriptionEN' => '',
        'descriptionID' => '',
    ];

    public array $database = [
        'titleEN' => '',
        'titleID' => '',
        'descriptionEN' => '',
        'descriptionID' => '',
    ];

    public array $gallery = [
        'database_id' => '',
        'titleEN' => '',
        'titleID' => '',
        'descriptionEN' => '',
        'descriptionID' => '',
        'type' => 'image',
        'path' => '',
    ];

    public $reportsList = [];
    public $databasesList = [];
    public $gallerysList = [];
    public $databaseOptions = [];

    public function mount(): void
    {
        $this->reloadLists();
    }

    public function setSection(string $sec): void
    {
        $this->section = in_array($sec, ['report', 'database', 'gallery'], true) ? $sec : 'report';
        $this->cancelEdit();
    }

    public function setLang(string $l): void
    {
        $this->lang = in_array($l, ['en', 'id'], true) ? $l : 'en';
    }

    public function cancelEdit(): void
    {
        $this->editMode = false;
        $this->editingSection = null;
        $this->editingId = null;
        $this->resetReportForm();
        $this->resetDatabaseForm();
        $this->resetGalleryForm();
    }

    public function saveReport(): void
    {
        $this->validate($this->rulesForReport());
        DB::table(self::TBL_REPORTS)->insert([
            'title_en' => $this->report['titleEN'] ?: null,
            'title_id' => $this->report['titleID'] ?: null,
            'description_en' => $this->report['descriptionEN'] ?: null,
            'description_id' => $this->report['descriptionID'] ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        session()->flash('successReport', 'Report saved.');
        $this->resetReportForm();
        $this->reloadLists();
    }

    public function editReport(int $id): void
    {
        $row = DB::table(self::TBL_REPORTS)->find($id);
        if (!$row) return;
        $this->editingId = $id;
        $this->editingSection = 'report';
        $this->editMode = true;
        $this->report['titleEN'] = $row->title_en ?? '';
        $this->report['titleID'] = $row->title_id ?? '';
        $this->report['descriptionEN'] = $row->description_en ?? '';
        $this->report['descriptionID'] = $row->description_id ?? '';
    }

    public function updateReport(): void
    {
        if (!$this->editMode || $this->editingSection !== 'report' || !$this->editingId) return;
        $this->validate($this->rulesForReport());
        DB::table(self::TBL_REPORTS)->where('id', $this->editingId)->update([
            'title_en' => $this->report['titleEN'] ?: null,
            'title_id' => $this->report['titleID'] ?: null,
            'description_en' => $this->report['descriptionEN'] ?: null,
            'description_id' => $this->report['descriptionID'] ?: null,
            'updated_at' => now(),
        ]);
        session()->flash('successReport', 'Report updated.');
        $this->cancelEdit();
        $this->reloadLists();
    }

    public function saveDatabase(): void
    {
        $this->validate($this->rulesForDatabase());
        DB::table(self::TBL_DATABASES)->insert([
            'title_en' => $this->database['titleEN'] ?: null,
            'title_id' => $this->database['titleID'] ?: null,
            'description_en' => $this->database['descriptionEN'] ?: null,
            'description_id' => $this->database['descriptionID'] ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        session()->flash('successDatabase', 'Database saved.');
        $this->resetDatabaseForm();
        $this->reloadLists();
    }

    public function editDatabase(int $id): void
    {
        $row = DB::table(self::TBL_DATABASES)->find($id);
        if (!$row) return;
        $this->editingId = $id;
        $this->editingSection = 'database';
        $this->editMode = true;
        $this->database['titleEN'] = $row->title_en ?? '';
        $this->database['titleID'] = $row->title_id ?? '';
        $this->database['descriptionEN'] = $row->description_en ?? '';
        $this->database['descriptionID'] = $row->description_id ?? '';
    }

    public function updateDatabase(): void
    {
        if (!$this->editMode || $this->editingSection !== 'database' || !$this->editingId) return;
        $this->validate($this->rulesForDatabase());
        DB::table(self::TBL_DATABASES)->where('id', $this->editingId)->update([
            'title_en' => $this->database['titleEN'] ?: null,
            'title_id' => $this->database['titleID'] ?: null,
            'description_en' => $this->database['descriptionEN'] ?: null,
            'description_id' => $this->database['descriptionID'] ?: null,
            'updated_at' => now(),
        ]);
        session()->flash('successDatabase', 'Database updated.');
        $this->cancelEdit();
        $this->reloadLists();
    }

    public function saveGallery(): void
    {
        $this->validate($this->rulesForGallery());
        DB::table(self::TBL_GALLERYS)->insert([
            'database_id' => $this->gallery['database_id'],
            'title_en' => $this->gallery['titleEN'] ?: null,
            'title_id' => $this->gallery['titleID'] ?: null,
            'description_en' => $this->gallery['descriptionEN'] ?: null,
            'description_id' => $this->gallery['descriptionID'] ?: null,
            'type' => $this->gallery['type'] ?: 'image',
            'path' => $this->gallery['path'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        session()->flash('successGallery', 'Gallery saved.');
        $this->resetGalleryForm();
        $this->reloadLists();
    }

    public function editGallery(int $id): void
    {
        $row = DB::table(self::TBL_GALLERYS)->find($id);
        if (!$row) return;
        $this->editingId = $id;
        $this->editingSection = 'gallery';
        $this->editMode = true;
        $this->gallery['database_id'] = (string)($row->database_id ?? '');
        $this->gallery['titleEN'] = $row->title_en ?? '';
        $this->gallery['titleID'] = $row->title_id ?? '';
        $this->gallery['descriptionEN'] = $row->description_en ?? '';
        $this->gallery['descriptionID'] = $row->description_id ?? '';
        $this->gallery['type'] = $row->type ?? 'image';
        $this->gallery['path'] = $row->path ?? '';
    }

    public function updateGallery(): void
    {
        if (!$this->editMode || $this->editingSection !== 'gallery' || !$this->editingId) return;
        $this->validate($this->rulesForGallery());
        DB::table(self::TBL_GALLERYS)->where('id', $this->editingId)->update([
            'database_id' => $this->gallery['database_id'],
            'title_en' => $this->gallery['titleEN'] ?: null,
            'title_id' => $this->gallery['titleID'] ?: null,
            'description_en' => $this->gallery['descriptionEN'] ?: null,
            'description_id' => $this->gallery['descriptionID'] ?: null,
            'type' => $this->gallery['type'] ?: 'image',
            'path' => $this->gallery['path'],
            'updated_at' => now(),
        ]);
        session()->flash('successGallery', 'Gallery updated.');
        $this->cancelEdit();
        $this->reloadLists();
    }

    private function reloadLists(): void
    {
        $this->reportsList = DB::table(self::TBL_REPORTS)->orderByDesc('id')->get();
        $this->databasesList = DB::table(self::TBL_DATABASES)->orderByDesc('id')->get();
        $this->gallerysList = DB::table(self::TBL_GALLERYS)->orderByDesc('id')->get();
        $this->databaseOptions = DB::table(self::TBL_DATABASES)->orderBy('title_en')->get();
    }

    private function resetReportForm(): void
    {
        $this->report = [
            'titleEN' => '',
            'titleID' => '',
            'descriptionEN' => '',
            'descriptionID' => '',
        ];
    }

    private function resetDatabaseForm(): void
    {
        $this->database = [
            'titleEN' => '',
            'titleID' => '',
            'descriptionEN' => '',
            'descriptionID' => '',
        ];
    }

    private function resetGalleryForm(): void
    {
        $this->gallery = [
            'database_id' => '',
            'titleEN' => '',
            'titleID' => '',
            'descriptionEN' => '',
            'descriptionID' => '',
            'type' => 'image',
            'path' => '',
        ];
    }

    private function rulesForReport(): array
    {
        return [
            'report.titleEN' => $this->lang === 'en' ? 'required|string' : 'required|string',
            'report.titleID' => $this->lang === 'id' ? 'required|string' : 'required|string',
            'report.descriptionEN' => $this->lang === 'en' ? 'required|string' : 'required|string',
            'report.descriptionID' => $this->lang === 'id' ? 'required|string' : 'required|string',
        ];
    }

    private function rulesForDatabase(): array
    {
        return [
            'database.titleEN' => $this->lang === 'en' ? 'required|string' : 'required|string',
            'database.titleID' => $this->lang === 'id' ? 'required|string' : 'required|string',
            'database.descriptionEN' => $this->lang === 'en' ? 'required|string' : 'required|string',
            'database.descriptionID' => $this->lang === 'id' ? 'required|string' : 'required|string',
        ];
    }

    private function rulesForGallery(): array
    {
        return [
            'gallery.database_id' => 'required|integer|exists:databases,id',
            'gallery.titleEN' => $this->lang === 'en' ? 'required|string' : 'required|string',
            'gallery.titleID' => $this->lang === 'id' ? 'required|string' : 'required|string',
            'gallery.descriptionEN' => $this->lang === 'en' ? 'required|string' : 'required|string',
            'gallery.descriptionID' => $this->lang === 'id' ? 'required|string' : 'required|string',
            'gallery.type' => ['required', Rule::in(['image', 'video'])],
            'gallery.path' => 'required|string',
        ];
    }

    public function render()
    {
        return view('livewire.cms.page-resource');
    }
}
