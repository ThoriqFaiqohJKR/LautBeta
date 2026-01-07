<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddAgenda extends Component
{
    use WithFileUploads;

    public string $lang = 'en';
    public string $type = 'event';

    public string $title_en = '';
    public string $title_id = '';
    public string $description_en = '';
    public string $description_id = '';
    public string $content_en = '';
    public string $content_id = '';
    public ?string $tanggal_publikasi = null;
    public string $publikasi = 'draf';

    public $image;
    public $file_id;
    public $file_en;
    public ?string $imagePreview = null;
    protected function rules()
    {
        return [
            'title_en' => 'required',
            'title_id' => 'required',
            'description_en' => 'required',
            'description_id' => 'required',
            'content_en' => 'required',
            'content_id' => 'required',
            'tanggal_publikasi' => 'required',
            'publikasi' => 'required',

            'file_id' => $this->type === 'activity' ? 'required|file|mimes:pdf,doc,docx' : 'nullable',
            'file_en' => $this->type === 'activity' ? 'required|file|mimes:pdf,doc,docx' : 'nullable',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title_en'          => $this->title_en,
            'title_id'          => $this->title_id,
            'description_en'    => $this->description_en,
            'description_id'    => $this->description_id,
            'content_en'        => $this->content_en,
            'content_id'        => $this->content_id,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'publikasi'         => $this->publikasi,
            'status'            => 'on',
            'type'              => $this->type,
            'slug'              => Str::slug($this->title_id),
            'created_at'        => now(),
            'updated_at'        => now(),
        ];

        if ($this->image) {
            $path = $this->image->store("agenda/{$this->type}", 'public');
            $data['image'] = $path;
        }

        if ($this->type === 'activity') {
            if ($this->file_id) {
                $original = pathinfo($this->file_id->getClientOriginalName(), PATHINFO_FILENAME);
                $ext      = $this->file_id->getClientOriginalExtension();
                $clean    = Str::slug($original) . '-' . time() . '.' . $ext;

                $data['file_id'] = $this->file_id->storeAs('agenda/files', $clean, 'public');
            }
            if ($this->file_en) {
                $original = pathinfo($this->file_en->getClientOriginalName(), PATHINFO_FILENAME);
                $ext      = $this->file_en->getClientOriginalExtension();
                $clean    = Str::slug($original) . '-' . time() . '.' . $ext;

                $data['file_en'] = $this->file_en->storeAs('agenda/files', $clean, 'public');
            }
        }

        DB::table('agenda')->insert($data);

        return redirect()
            ->route('cms.page.index.agenda', ['locale' => app()->getLocale()])
            ->with('success', 'Agenda berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.cms.add-agenda');
    }
}
