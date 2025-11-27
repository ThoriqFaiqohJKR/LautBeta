<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IndexInfografik extends Component
{
    public $confirmingDelete = false;
    public $deleteId = null;
    public $items = [];

    public function mount()
    {
        $this->loadItems();
    }

    public function loadItems()
    {
        $this->items = DB::table('infografik')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {
                $firstImg = DB::table('infografik_images')
                    ->where('infografik_id', $item->id)
                    ->value('image');

                $item->first_image = $firstImg
                    ? asset('storage/' . $firstImg)
                    : null;

                return $item;
            });
    }

    public function openDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        $id = $this->deleteId;


        $images = DB::table('infografik_images')
            ->where('infografik_id', $id)
            ->get();

        foreach ($images as $img) {
            if ($img->image && Storage::exists('public/'.$img->image)) {
                Storage::delete('public/'.$img->image);
            }
        }

        DB::table('infografik_images')->where('infografik_id', $id)->delete();
        DB::table('infografik')->where('id', $id)->delete();

        $this->confirmingDelete = false;
        $this->deleteId = null;

        $this->loadItems();
    }

    public function render()
    {
        return view('livewire.cms.index-infografik');
    }
}
