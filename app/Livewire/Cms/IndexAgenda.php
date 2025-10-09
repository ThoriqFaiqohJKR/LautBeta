<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class IndexAgenda extends Component
{
    public string $type = 'all';
    public string $publikasi = 'all';
    public string $q = '';
    public string $sort = 'latest';
    public int $page = 1;
    public int $perPage = 6;
    public int $total = 0;

    protected $queryString = [
        'type'    => ['except' => 'all'],
        'publikasi' => ['except' => 'all'],
        'q'       => ['except' => ''],
        'sort'    => ['except' => 'latest'],
        'page'    => ['except' => 1],
        'perPage' => ['except' => 6],
    ];

    public function mount(): void
    {
        $t = request('type');
        if (in_array($t, ['all', 'event', 'activity'], true)) {
            $this->type = $t;
        }
    }

    public function updating($name, $value): void
    {
        if (in_array($name, ['type', 'publikasi', 'q', 'sort', 'perPage'], true)) {
            $this->page = 1;
        }
    }

    private function baseQuery()
    {
        // Ambil locale dari URL (navbar kamu sudah set ini), fallback ke app locale
        $locale = request()->route('locale') ?? app()->getLocale() ?? 'en';
        $titleCol = $locale === 'id' ? 'title_id' : 'title_en';
        $descCol  = $locale === 'id' ? 'description_id' : 'description_en';

        return DB::table('agenda')
            ->select(
                'id',
                'slug',
                'image',
                'type',
                'tanggal_publikasi',
                'publikasi',
                // kirim dua-duanya ke view
                'title_en',
                'title_id',
                // alias yang dipakai tabel (otomatis sesuai locale)
                DB::raw("$titleCol AS title"),
                DB::raw("$descCol  AS description")
            )
            ->where('status', 'on')
            ->when($this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->when($this->publikasi !== 'all', fn($q) => $q->where('publikasi', $this->publikasi))
            ->when($this->q !== '', function ($q) use ($titleCol, $descCol) {
                $s = '%' . $this->q . '%';
                $q->where(function ($w) use ($titleCol, $descCol, $s) {
                    $w->where($titleCol, 'like', $s)
                        ->orWhere($descCol,  'like', $s)
                        ->orWhere('slug',    'like', $s);
                });
            });
    }

    private function applySorting($query)
    {
        return match ($this->sort) {
            'oldest' => $query->orderBy('tanggal_publikasi')->orderBy('id'),
            'az'     => $query->orderBy('title')->orderByDesc('id'),
            'za'     => $query->orderBy('title', 'desc')->orderByDesc('id'),
            default  => $query->orderByDesc('tanggal_publikasi')->orderByDesc('id'),
        };
    }

    public function getagendas()
    {
        $query = $this->baseQuery();

        $this->total = (int) (clone $query)->count();
        $this->page  = min($this->page, $this->lastPage());

        $query = $this->applySorting($query);

        return $query
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    public function lastPage(): int
    {
        return max(1, (int) ceil($this->total / $this->perPage));
    }

    public function nextPage(): void
    {
        if ($this->page < $this->lastPage()) $this->page++;
    }

    public function prevPage(): void
    {
        if ($this->page > 1) $this->page--;
    }

    public function render()
    {
        return view('livewire.cms.index-agenda', [
            'agendas'  => $this->getagendas(),
            'page'     => $this->page,
            'lastPage' => $this->lastPage(),
            'total'    => $this->total,
            'perPage'  => $this->perPage,
        ]);
    }
}
