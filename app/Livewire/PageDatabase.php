<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PageDatabase extends Component
{
    public array $groups = [];

    public function mount(): void
    {
        $loc = app()->getLocale();

        $rows = DB::table('database')
            ->select(['id', 'title_en', 'title_id', 'description_en', 'description_id', 'slug'])
            ->orderBy('title_en')
            ->get();

        $items = $rows->map(function ($r) use ($loc) {
            $title = $loc === 'id' ? ($r->title_id ?: $r->title_en) : ($r->title_en ?: $r->title_id);
            $desc  = $loc === 'id' ? ($r->description_id ?: $r->description_en) : ($r->description_en ?: $r->description_id);
            return (object)[
                'id'    => $r->id,
                'title' => $title ?: 'Untitled',
                'desc'  => $desc,
                'slug'  => $r->slug,
            ];
        });

        $groups = [];
        foreach ($items as $it) {
            $ltr = mb_strtoupper(mb_substr($it->title, 0, 1));
            if (!preg_match('/[A-Z]/u', $ltr)) $ltr = '#';
            $groups[$ltr][] = $it;
        }

        $this->groups = $groups;
    }

    public function render()
    {
        return view('livewire.page-database', ['groups' => $this->groups]);
    }
}
