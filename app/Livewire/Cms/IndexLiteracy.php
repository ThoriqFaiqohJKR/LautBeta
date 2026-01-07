<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class IndexLiteracy extends Component
{
    public string $type = 'all';
    public string $publikasi = 'all';
    public string $q = '';
    public string $sort = 'latest';

    public int $page = 1;
    public int $perPage = 10;
    public int $total = 0;

    protected $queryString = [
        'type'      => ['except' => 'all'],
        'publikasi' => ['except' => 'all'],
        'q'         => ['except' => ''],
        'sort'      => ['except' => 'latest'],
        'page'      => ['except' => 1],
        'perPage'   => ['except' => 6],
    ];


    public function updated($field)
    {
        if (in_array($field, ['type', 'publikasi', 'q', 'sort', 'perPage'])) {
            $this->page = 1;
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

    private function baseQuery()
    {
        $cols = $this->localeColumns();

        return DB::table('literacy')
            ->select(
                'id',
                'slug',
                'image',
                'type',
                'tanggal_publikasi',
                'publikasi',
                DB::raw("{$cols['title']} as title"),
                DB::raw("{$cols['description']} as description")
            )
            ->where('status', 'on')
            ->when($this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->when($this->publikasi !== 'all', fn($q) => $q->where('publikasi', $this->publikasi))
            ->when($this->q !== '', function ($q) use ($cols) {
                $s = '%' . $this->q . '%';
                $q->where(function ($w) use ($cols, $s) {
                    $w->where($cols['title'], 'like', $s)
                        ->orWhere($cols['description'], 'like', $s)
                        ->orWhere('slug', 'like', $s);
                });
            });
    }

    public function lastPage(): int
    {
        return max(1, (int) ceil($this->total / $this->perPage));
    }

    public function goToPage($page)
    {
        $this->page = max(1, min($page, $this->lastPage()));
    }

    public function prevPage()
    {
        $this->goToPage($this->page - 1);
    }

    public function nextPage()
    {
        $this->goToPage($this->page + 1);
    }

    public function getLiteracys()
    {
        $query = $this->baseQuery();


        $this->total = $query->count();


        $query = match ($this->sort) {
            'oldest' => $query->orderBy('updated_at')->orderBy('id'),
            'az'     => $query->orderBy('title'),
            'za'     => $query->orderBy('title', 'desc'),
            default  => $query->orderByDesc('updated_at')->orderByDesc('id'),
        };


        return $query
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    public function render()
    {
        return view('livewire.cms.index-literacy', [
            'literacys' => $this->getLiteracys(),
            'page'     => $this->page,
            'lastPage' => $this->lastPage(),
            'total'    => $this->total,
            'perPage'  => $this->perPage,
        ]);
    }
}
