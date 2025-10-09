<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class IndexInsight extends Component
{
    public string $type = 'all';
    public string $publikasi = 'all';
    public string $q = '';
    public string $sort = 'latest';

    public int $page = 1;
    public int $perPage = 6;
    public int $total = 0;

    protected $queryString = [
        'type'      => ['except' => 'all'],
        'publikasi' => ['except' => 'all'],
        'q'         => ['except' => ''],
        'sort'      => ['except' => 'latest'],
        'page'      => ['except' => 1],
        'perPage'   => ['except' => 6],
    ];

    public function mount(): void
    {
        $t = request('type');
        if (in_array($t, ['all', 'feature', 'analysis', 'ngopini'], true)) {
            $this->type = $t;
        }
    }


    public function updatingType()
    {
        $this->page = 1;
    }
    public function updatingPublikasi()
    {
        $this->page = 1;
    }
    public function updatingQ()
    {
        $this->page = 1;
    }
    public function updatingSort()
    {
        $this->page = 1;
    }
    public function updatingPerPage()
    {
        $this->page = 1;
    }

    private function currentLocale(): string
    {
        return request()->route('locale') ?? app()->getLocale();
    }

    private function baseQuery()
    {
        $locale   = $this->currentLocale();
        $titleCol = $locale === 'id' ? 'title_id' : 'title_en';
        $descCol  = $locale === 'id' ? 'description_id' : 'description_en';

        return DB::table('insight')
            ->select(
                'id',
                'slug',
                'image',
                'type',
                'tanggal_publikasi',
                'publikasi',
                DB::raw("$titleCol as title"),
                DB::raw("$descCol as description")
            )
            ->where('status', 'on')
            ->when($this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->when($this->publikasi !== 'all', fn($q) => $q->where('publikasi', $this->publikasi))
            ->when($this->q !== '', function ($q) use ($titleCol, $descCol) {
                $s = '%' . $this->q . '%';
                $q->where(function ($w) use ($titleCol, $descCol, $s) {
                    $w->where($titleCol, 'like', $s)
                        ->orWhere($descCol, 'like', $s)
                        ->orWhere('slug', 'like', $s);
                });
            });
    }

    public function getInsights()
    {
        $base = $this->baseQuery();
        $this->total = (int) $base->count();

        $base = match ($this->sort) {
            'oldest' => $base->orderBy('updated_at')->orderBy('id'),
            'az'     => $base->orderBy('title')->orderByDesc('id'),
            'za'     => $base->orderBy('title', 'desc')->orderByDesc('id'),
            default  => $base->orderByDesc('updated_at')->orderByDesc('id'),
        };


        $lp = $this->lastPage();
        if ($this->page > $lp) $this->page = $lp;

        return $base
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
        return view('livewire.cms.index-insight', [
            'insights' => $this->getInsights(),
            'page'     => $this->page,
            'lastPage' => $this->lastPage(),
            'total'    => $this->total,
            'perPage'  => $this->perPage,
        ]);
    }
}
