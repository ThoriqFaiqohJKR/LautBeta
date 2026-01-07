<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IndexResource extends Component
{
    use WithPagination;

    public string $data = 'all';
    public string $type = 'all';
    public string $publikasi = 'all';
    public string $q = '';
    public string $sort = 'latest';

    public int $page = 1;
    public int $page_gallery = 1; // pagination terpisah untuk gallery
    public int $perPage = 10;
    public int $total = 0;

    protected $queryString = [
        'data'         => ['except' => 'all'],
        'type'         => ['except' => 'all'],
        'publikasi'    => ['except' => 'all'],
        'q'            => ['except' => ''],
        'sort'         => ['except' => 'latest'],
        'page'         => ['except' => 1],
        'page_gallery' => ['except' => 1],
        'perPage'      => ['except' => 10],
    ];

    public function updated($field)
    {
        // Reset kedua paginator saat filter berubah
        if (in_array($field, ['data', 'type', 'publikasi', 'q', 'sort', 'perPage'])) {
            $this->page = 1;
            $this->page_gallery = 1;
        }
    }

    private function localeColumns(): array
    {
        $locale = request()->route('locale') ?? app()->getLocale();
        return [
            'title'       => $locale === 'id' ? 'title_id' : 'title_en',
            'description' => $locale === 'id' ? 'description_id' : 'description_en',
        ];
    }

    private function baseReportsQuery()
    {
        $cols = $this->localeColumns();

        $q = DB::table('report')
            ->select(
                'id',
                'slug',
                'image',
                'tanggal_publikasi',
                'publikasi',
                'created_at',
                'updated_at',
                DB::raw("{$cols['title']} as title"),
                DB::raw("{$cols['description']} as description")
            )
            ->where('status', 'on');

        if ($this->publikasi !== 'all') $q->where('publikasi', $this->publikasi);

        if ($this->q !== '') {
            $s = '%' . $this->q . '%';
            $q->where(function ($w) use ($cols, $s) {
                $w->where($cols['title'], 'like', $s)
                    ->orWhere($cols['description'], 'like', $s)
                    ->orWhere('slug', 'like', $s);
            });
        }

        return $q;
    }

    private function baseGalleryQuery()
    {
        $q = DB::table('gallery')
            ->select('id', 'filename', 'type', 'publikasi', 'path', 'caption', 'created_at', 'updated_at');

        if ($this->publikasi !== 'all') $q->where('publikasi', $this->publikasi);
        if ($this->type !== 'all') $q->where('type', $this->type);

        if ($this->q !== '') {
            $s = '%' . $this->q . '%';
            $q->where(function ($w) use ($s) {
                $w->where('filename', 'like', $s)
                    ->orWhere('caption', 'like', $s);
            });
        }

        return $q;
    }

    public function lastPage(): int
    {
        return max(1, (int) ceil($this->total / $this->perPage));
    }

    public function goToPage($page)
    {
        $this->page = max(1, min((int)$page, $this->lastPage()));
    }

    public function prevPage()
    {
        $this->goToPage($this->page - 1);
    }

    public function nextPage()
    {
        $this->goToPage($this->page + 1);
    }

    public function deleteReport($id)
    {
        $gallery = DB::table('gallery')->where('report_id', $id)->get(['id', 'path']);
        foreach ($gallery as $g) {
            if ($g->path && Storage::exists($g->path)) Storage::delete($g->path);
            DB::table('gallery')->where('id', $g->id)->delete();
        }
        DB::table('report')->where('id', $id)->delete();

        // reset pagination reports ke halaman 1 jika perlu
        $this->page = 1;

        session()->flash('success', 'Report dihapus');
    }

    public function deleteGallery($id)
    {
        $g = DB::table('gallery')->where('id', $id)->first();
        if (!$g) {
            session()->flash('success', 'Data tidak ditemukan');
            return;
        }

        // Hapus file utama gallery jika ada
        if (!empty($g->path) && Storage::exists($g->path)) {
            Storage::delete($g->path);
        }

        // Hapus semua gambar yang terkait di tabel gallery_image (jika ada)
        $images = DB::table('gallery_image')->where('gallery_id', $id)->get(['id', 'path']);
        foreach ($images as $img) {
            if (!empty($img->path) && Storage::exists($img->path)) {
                Storage::delete($img->path);
            }
            DB::table('gallery_image')->where('id', $img->id)->delete();
        }

        // Hapus record gallery
        DB::table('gallery')->where('id', $id)->delete();

        // Reset gallery pagination (atau atur ke halaman terakhir yg valid jika mau)
        $this->page_gallery = 1;

        $this->emit('galleryDeleted'); // opsional: untuk event JS/notify
        session()->flash('success', 'Gallery dan gambar terkait berhasil dihapus');
    }

    public function getReports()
    {
        $q = $this->baseReportsQuery();

        $this->total = (int) $q->count();

        $q = match ($this->sort) {
            'oldest' => $q->orderBy('updated_at', 'asc')->orderBy('id', 'asc'),
            'az'     => $q->orderBy('title', 'asc'),
            'za'     => $q->orderBy('title', 'desc'),
            default  => $q->orderByDesc('updated_at')->orderByDesc('id'),
        };

        return $q->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    public function getGallery()
    {
        $q = $this->baseGalleryQuery();

        if ($this->sort === 'az') $q->orderBy('filename', 'asc');
        elseif ($this->sort === 'za') $q->orderBy('filename', 'desc');

        // paginate(perPage, columns, pageName, page)
        return $q->paginate($this->perPage, ['*'], 'page_gallery', $this->page_gallery);
    }

    public function render()
    {
        $reports = collect();
        $gallery = collect();

        if ($this->data !== 'gallery') {
            $reports = $this->getReports();
        }

        if ($this->data !== 'report') {
            $gallery = $this->getGallery();
        }

        return view('livewire.cms.index-resource', [
            'resource'     => $reports,
            'gallery'      => $gallery,
            'page'         => $this->page,
            'page_gallery' => $this->page_gallery,
            'perPage'      => $this->perPage,
            'lastPage'     => $this->lastPage(),
            'total'        => $this->total,
        ]);
    }
}
