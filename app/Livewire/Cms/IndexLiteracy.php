<?php 

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class IndexLiteracy extends Component
{
    public string $q = '';

    private function currentLocale(): string
    {

        return request()->route('locale') ?? app()->getLocale() ?? 'en';
    }

    public function render()
    {
        $locale   = $this->currentLocale();
        $titleCol = $locale === 'id' ? 'title_id' : 'title_en';


        $journals = DB::table('journal')
            ->select(
                'id',
                DB::raw("$titleCol AS title"),
                'tanggal_publikasi',
                'publikasi',

                DB::raw(
                    $locale === 'id'
                        ? "COALESCE(file_id, file_en) AS file_url"
                        : "COALESCE(file_en, file_id) AS file_url"
                ),
                'created_at'
            )
            ->when($this->q !== '', function ($q) use ($titleCol) {
                $s = '%' . $this->q . '%';
                $q->where($titleCol, 'like', $s);
            })
            ->orderByDesc('id')
            ->get();




        return view('livewire.cms.index-literacy', [
            'journals'    => $journals,

            'locale'      => $locale,
        ]);
    }
}
