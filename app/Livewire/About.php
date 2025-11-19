<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class About extends Component
{
    public $about = null;
    public $lang  = 'id';

    public function mount()
    {

        $current = app()->getLocale();
        $this->lang = in_array($current, ['id', 'en']) ? $current : 'id';


        $row = DB::table('abouts')
            ->select('title_id', 'title_en', 'description_id', 'description_en')
            ->first();

        if ($row) {
            
            $title = $this->lang === 'en' ? ($row->title_en ?? null) : ($row->title_id ?? null);
            $desc  = $this->lang === 'en' ? ($row->description_en ?? null) : ($row->description_id ?? null);

            if (!$title) $title = $row->title_id ?? $row->title_en ?? '';
            if (!$desc)  $desc  = $row->description_id ?? $row->description_en ?? '';

            $this->about = [
                'title'       => $title,
                'description' => $desc,
            ];
        }
    }

    public function render()
    {
        return view('livewire.about');
    }
}
