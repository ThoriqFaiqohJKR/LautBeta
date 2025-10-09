<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class IndexLiteracy extends Component
{
    public string $q = '';

    private function currentLocale(): string
    {
        // Ambil dari route {locale} kalau ada, fallback ke app()->getLocale()
        return request()->route('locale') ?? app()->getLocale() ?? 'en';
    }

    public function render()
    {
        $locale   = $this->currentLocale();
        $titleCol = $locale === 'id' ? 'title_id' : 'title_en';
        $descCol  = $locale === 'id' ? 'description_id' : 'description_en';

        
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

        
        $caseReports = DB::table('case_report')
            ->select(
                'id',
                DB::raw("$titleCol AS title"),
                DB::raw("$descCol AS description"),
                'tanggal_publikasi',
                'publikasi',
                'status',
                'slug',
                'created_at'
            )
            ->when($this->q !== '', function ($q) use ($titleCol, $descCol) {
                $s = '%' . $this->q . '%';
                $q->where(function ($w) use ($titleCol, $descCol, $s) {
                    $w->where($titleCol, 'like', $s)
                        ->orWhere($descCol, 'like', $s)
                        ->orWhere('slug', 'like', $s);
                });
            })
            ->orderByDesc('id')
            ->get();

        return view('livewire.cms.index-literacy', [
            'journals'    => $journals,
            'caseReports' => $caseReports,
            'locale'      => $locale, 
        ]);
    }
}
