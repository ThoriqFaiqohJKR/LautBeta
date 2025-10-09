<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PreviewResource extends Component
{
    public string $type = 'report'; 
    public ?int $id = null;

    public function mount($type = 'report', $id = null)
    {
        $this->type = in_array($type, ['report', 'database']);
        $this->id = $id ? (int)$id : 0;
    }

    public function render()
    {
        $id = (int) ($this->id ?? 0);
        $type = in_array($this->type, ['report', 'database'], true);

        $existsReport   = DB::table('report')->where('id', $id)->exists();
        $existsDatabase = DB::table('database')->where('id', $id)->exists();

        $table = $existsReport ? 'report' : ($existsDatabase ? 'database' : null);
        if (!$table) abort(404, 'Data tidak ditemukan');

        $item = DB::table($table)->where('id', $id)->first();

        return view('livewire.cms.preview-resource', [
            'type' => $table,
            'item' => $item,
        ]);
    }
}
