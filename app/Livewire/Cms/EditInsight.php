<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class EditInsight extends Component
{
    use WithFileUploads;

    public int|string $id = 0;
    public string $lang = 'en';
    public string $type;

    public string $title_en = '';
    public string $title_id = '';
    public string $description_en = '';
    public string $description_id = '';
    public string $content_en = '';
    public string $content_id = '';
    public ?string $tanggal_publikasi = null;
    public string $publikasi = 'draf';
    public string $status = 'on';
    public ?string $slug = null;
    public $image = null;
    public ?string $imagePreview = null;

    public function mount($id): void
    {
        $this->id = (int) $id;

        $data = DB::table('insight')->find($this->id);
        if (!$data) abort(404);

        $this->type              = $data->type;
        $this->title_en          = $data->title_en ?? '';
        $this->title_id          = $data->title_id ?? '';
        $this->description_en    = $data->description_en ?? '';
        $this->description_id    = $data->description_id ?? '';
        $this->content_en        = $data->content_en ?? '';
        $this->content_id        = $data->content_id ?? '';
        $this->tanggal_publikasi = $data->tanggal_publikasi;
        $this->publikasi         = $data->publikasi;
        $this->status            = $data->status;
        $this->slug              = $data->slug;
        $this->imagePreview      = $data->image ? Storage::url($data->image) : null;
    }

    protected function rules(): array
    {
        return [
            'title_en'          => ['required', 'string', 'max:255'],
            'title_id'          => ['required', 'string', 'max:255'],
            'description_en'    => ['required', 'string'],
            'description_id'    => ['required', 'string'],
            'content_en'        => ['required', 'string'],
            'content_id'        => ['required', 'string'],
            'tanggal_publikasi' => ['required', 'date'],
            'publikasi'         => ['required', 'in:draf,publish'],
            'status'            => ['required', 'in:on,off'],
            'image'             => ['required', File::image()->max(5 * 1024)],
        ];
    }

    public function update(): void
    {
        $this->validate();

        $data = [
            'title_en'          => $this->title_en ?: null,
            'title_id'          => $this->title_id ?: null,
            'description_en'    => $this->description_en ?: null,
            'description_id'    => $this->description_id ?: null,
            'content_en'        => $this->content_en ?: null,
            'content_id'        => $this->content_id ?: null,
            'tanggal_publikasi' => $this->tanggal_publikasi,
            'publikasi'         => $this->publikasi,
            'status'            => $this->status,
            'updated_at'        => now(),
        ];

        if ($this->image) {
            $path = $this->image->store("insight/{$this->type}", 'public');
            $data['image'] = $path;
            $this->imagePreview = Storage::url($path);
        }

        DB::table('insight')->where('id', (int) $this->id)->update($data);

        session()->flash('success', 'Insight berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.cms.edit-insight');
    }
}
