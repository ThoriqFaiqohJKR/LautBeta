<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PageLiteracy extends Component
{
    use WithFileUploads;

    /** UI state */
    public string $section = 'reports';      // 'reports' | 'journal'
    public string $lang    = 'en';           // 'en' | 'id'
    public string $imageMode = 'upload';     // untuk Reports: 'upload' | 'url'

    public bool $editMode = false;
    public ?string $editingSection = null;   // 'reports' | 'journal'
    public ?int $editingId = null;

    /** Reports form (pakai nested array biar rapi) */
    public array $reports = [
        'titleEN'       => '',
        'titleID'       => '',
        'descriptionEN' => '',
        'descriptionID' => '',
        'image'         => null,   // UploadedFile
        'imageUrl'      => null,   // string
    ];
    public ?string $reportsImagePath = null; // url untuk preview kalau edit

    /** Journal form */
    public array $journal = [
        'titleEN'        => '',
        'titleID'        => '',
        'descriptionEN'  => '',
        'descriptionID'  => '',
        'image'          => null,  // UploadedFile (thumbnail)
        'attachment'     => null,  // UploadedFile
    ];
    public ?string $journalImagePath       = null; // url untuk preview kalau edit
    public ?string $journalAttachmentName  = null;
    public ?string $journalAttachmentUrl   = null;

    /** Lists */
    public $reportsList  = [];
    public $journalsList = [];

    /** Mount: muat list awal */
    public function mount(): void
    {
        $this->reloadLists();
    }

    /** Helpers untuk view actions */
    public function setSection(string $sec): void
    {
        $this->section = in_array($sec, ['reports', 'journal'], true) ? $sec : 'reports';
    }

    public function setLang(string $l): void
    {
        $this->lang = in_array($l, ['en', 'id'], true) ? $l : 'en';
    }

    /** Update hook: tampilkan nama lampiran saat dipilih (journal) */
    public function updatedJournalAttachment(): void
    {
        $file = $this->journal['attachment'] ?? null;
        if ($file && method_exists($file, 'getClientOriginalName')) {
            $this->journalAttachmentName = $file->getClientOriginalName();
        }
    }

    /** CRUD — REPORTS */
    public function saveReports(): void
    {
        $this->validate($this->rulesForReports());

        $path = null;
        // Upload file
        if ($this->imageMode === 'upload' && !empty($this->reports['image'])) {
            $path = $this->reports['image']->store('reports', 'public'); // simpan di storage/app/public/reports
        }
        // Atau gunakan URL tempel
        if ($this->imageMode === 'url' && !empty($this->reports['imageUrl'])) {
            $path = $this->reports['imageUrl'];
        }

        DB::table('case_reports')->insert([
            'title_en'       => $this->reports['titleEN'] ?: null,
            'title_id'       => $this->reports['titleID'] ?: null,
            'description_en' => $this->reports['descriptionEN'] ?: null,
            'description_id' => $this->reports['descriptionID'] ?: null,
            'image'          => $path,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);


        session()->flash('successReports', 'Reports saved.');
        $this->resetReportsForm();
        $this->reloadLists();
    }

    public function editReports(int $id): void
    {
        $row = DB::table('case_reports')->find($id);
        if (!$row) return;

        $this->editingId = $id;
        $this->editingSection = 'reports';
        $this->editMode = true;

        $this->reports['titleEN']       = $row->title_en ?? '';
        $this->reports['titleID']       = $row->title_id ?? '';
        $this->reports['descriptionEN'] = $row->description_en ?? '';
        $this->reports['descriptionID'] = $row->description_id ?? '';
        $this->reports['image']         = null;
        $this->reports['imageUrl']      = null;

        $this->reportsImagePath = $this->publicUrl($row->image);

        // Livewire v3: trigger event untuk isi TinyMCE
        $content = $this->lang === 'id'
            ? ($this->reports['descriptionID'] ?? '')
            : ($this->reports['descriptionEN'] ?? '');

        $this->js('window.dispatchEvent(new CustomEvent("tinymce-fill", { detail: { target: "reports_desc", content: "' . addslashes($content) . '" } }))');
    }


    public function updateReports(): void
    {
        if (!$this->editMode || $this->editingSection !== 'reports' || !$this->editingId) return;

        $this->validate($this->rulesForReports());

        $row = DB::table('case_reports')->find($this->editingId);
        if (!$row) return;

        $path = $row->image;
        if ($this->imageMode === 'upload' && !empty($this->reports['image'])) {
            $path = $this->reports['image']->store('reports', 'public');
        } elseif ($this->imageMode === 'url' && !empty($this->reports['imageUrl'])) {
            $path = $this->reports['imageUrl'];
        }

        DB::table('case_reports')->where('id', $this->editingId)->update([
            'title_en'       => $this->reports['titleEN'] ?: null,
            'title_id'       => $this->reports['titleID'] ?: null,
            'description_en' => $this->reports['descriptionEN'] ?: null,
            'description_id' => $this->reports['descriptionID'] ?: null,
            'image'          => $path,
            'updated_at'     => now(),
        ]);

        session()->flash('successReports', 'Reports updated.');
        $this->cancelEdit();
        $this->reloadLists();
    }

    /** CRUD — JOURNAL */
    public function saveJournal(): void
    {
        $this->validate($this->rulesForJournal());

        $img = null;
        $att = null;

        if (!empty($this->journal['image'])) {
            $img = $this->journal['image']->store('journals', 'public');
        }
        if (!empty($this->journal['attachment'])) {
            $att = $this->journal['attachment']->store('journals_attach', 'public');
        }

        DB::table('journals')->insert([
            'title_en'        => $this->journal['titleEN'] ?: null,
            'title_id'        => $this->journal['titleID'] ?: null,
            'description_en'  => $this->journal['descriptionEN'] ?: null,
            'description_id'  => $this->journal['descriptionID'] ?: null,
            'image'      => $img,
            'file' => $att,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        session()->flash('successJournal', 'Journal saved.');
        $this->resetJournalForm();
        $this->reloadLists();
    }

    public function editJournal(int $id): void
    {
        $row = DB::table('journals')->find($id);
        if (!$row) return;

        $this->editingId = $id;
        $this->editingSection = 'journal';
        $this->editMode = true;

        $this->journal['titleEN']        = $row->title_en ?? '';
        $this->journal['titleID']        = $row->title_id ?? '';
        $this->journal['descriptionEN']  = $row->description_en ?? '';
        $this->journal['descriptionID']  = $row->description_id ?? '';
        $this->journal['image']          = null;
        $this->journal['attachment']     = null;

        $this->journalImagePath      = $this->publicUrl($row->image);
        $this->journalAttachmentUrl  = $this->publicUrl($row->file);
        $this->journalAttachmentName = null;

        $content = $this->lang === 'id'
            ? ($this->journal['descriptionID'] ?? '')
            : ($this->journal['descriptionEN'] ?? '');

        $this->js('window.dispatchEvent(new CustomEvent("tinymce-fill", { detail: { target: "journal_desc", content: "' . addslashes($content) . '" } }))');
    }

    public function updateJournal(): void
    {
        if (!$this->editMode || $this->editingSection !== 'journal' || !$this->editingId) return;

        $this->validate($this->rulesForJournal());

        $row = DB::table('journals')->find($this->editingId);
        if (!$row) return;

        $img = $row->image;
        $att = $row->file;

        if (!empty($this->journal['image'])) {
            $img = $this->journal['image']->store('journals', 'public');
        }
        if (!empty($this->journal['attachment'])) {
            $att = $this->journal['attachment']->store('journals_attach', 'public');
        }

        DB::table('journals')->where('id', $this->editingId)->update([
            'title_en'        => $this->journal['titleEN'] ?: null,
            'title_id'        => $this->journal['titleID'] ?: null,
            'description_en'  => $this->journal['descriptionEN'] ?: null,
            'description_id'  => $this->journal['descriptionID'] ?: null,
            'image'      => $img,
            'file' => $att,
            'updated_at'      => now(),
        ]);

        session()->flash('successJournal', 'Journal updated.');
        $this->cancelEdit();
        $this->reloadLists();
    }

    /** Cancel editing & reset form, tapi tetap jaga lists */
    public function cancelEdit(): void
    {
        $this->editMode = false;
        $this->editingSection = null;
        $this->editingId = null;

        $this->resetReportsForm();
        $this->resetJournalForm();
        $this->imageMode = 'upload';
        $this->lang = 'en';
    }

    private function reloadLists(): void
    {
        $this->reportsList  = DB::table('case_reports')->orderByDesc('id')->get();
        $this->journalsList = DB::table('journals')->orderByDesc('id')->get();
    }


    /** Validation */
    private function rulesForReports(): array
    {
        return [
            // Title/Description wajib sesuai bahasa aktif (kalau mau longgar, ubah ke required)
            'reports.titleEN'       => $this->lang === 'en' ? 'required|string' : 'required|string',
            'reports.titleID'       => $this->lang === 'id' ? 'required|string' : 'required|string',
            'reports.descriptionEN' => $this->lang === 'en' ? 'required|string' : 'required|string',
            'reports.descriptionID' => $this->lang === 'id' ? 'required|string' : 'required|string',

            // Gambar: upload atau url (opsional saat update)
            'reports.image'   => $this->imageMode === 'upload' ? 'required|image|max:2048' : 'required',
            'reports.imageUrl' => $this->imageMode === 'url'    ? 'required|url'           : 'required',
        ];
    }

    private function rulesForJournal(): array
    {
        return [
            'journal.titleEN'        => $this->lang === 'en' ? 'required|string' : 'required|string',
            'journal.titleID'        => $this->lang === 'id' ? 'required|string' : 'required|string',
            'journal.descriptionEN'  => $this->lang === 'en' ? 'required|string' : 'required|string',
            'journal.descriptionID'  => $this->lang === 'id' ? 'required|string' : 'required|string',

            'journal.image'      => 'required|image|max:2048',
            'journal.attachment' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,jpeg,png,gif|max:10240',
        ];
    }

    /** Helpers */
    private function publicUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (preg_match('~^https?://~i', $path)) {
            // sudah full URL (misal hasil LFM copy-url)
            return $path;
        }
        // anggap path storage (public disk)
        return Storage::url($path);
    }

    private function resetReportsForm(): void
    {
        $this->reports = [
            'titleEN'       => '',
            'titleID'       => '',
            'descriptionEN' => '',
            'descriptionID' => '',
            'image'         => null,
            'imageUrl'      => null,
        ];
        $this->reportsImagePath = null;
    }

    private function resetJournalForm(): void
    {
        $this->journal = [
            'titleEN'        => '',
            'titleID'        => '',
            'descriptionEN'  => '',
            'descriptionID'  => '',
            'image'          => null,
            'attachment'     => null,
        ];
        $this->journalImagePath      = null;
        $this->journalAttachmentName = null;
        $this->journalAttachmentUrl  = null;
    }

    public function render()
    {
        return view('livewire.cms.page-literacy');
    }
}
