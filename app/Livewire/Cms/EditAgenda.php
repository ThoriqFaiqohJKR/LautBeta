<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class EditAgenda extends Component
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


    public $file_id = null;
    public $file_en = null;
    public ?string $current_file_id = null;
    public ?string $current_file_en = null;

    public function mount($id): void
    {
        $this->id = (int) $id;

        $data = DB::table('agenda')->find($this->id);
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


        $this->current_file_id = $data->file_id ?? null;
        $this->current_file_en = $data->file_en ?? null;


        $this->file_id = null;
        $this->file_en = null;
    }

    protected function rules(): array
    {

        $fileRules = $this->type === 'activity'
            ? ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240']
            : ['nullable'];

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
            'image'             => ['nullable', File::image()->max(5 * 1024)],
            'file_id'           => $fileRules,
            'file_en'           => $fileRules,
        ];
    }


    public function updatedLang($value)
    {
        $this->resetValidation();
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
            $path = $this->image->store("agenda/{$this->type}", 'public');
            $data['image'] = $path;
            $this->imagePreview = Storage::url($path);
        }


        if ($this->type === 'activity') {

            if ($this->file_id) {
                $original = pathinfo($this->file_id->getClientOriginalName(), PATHINFO_FILENAME);
                $ext      = $this->file_id->getClientOriginalExtension();
                $clean    = \Illuminate\Support\Str::slug($original) . '-' . time() . '.' . $ext;

                $newPath = $this->file_id->storeAs('agenda/files', $clean, 'public');
                $data['file_id'] = $newPath;

                if ($this->current_file_id && Storage::disk('public')->exists($this->current_file_id)) {
                    try {
                        Storage::disk('public')->delete($this->current_file_id);
                    } catch (\Exception $e) {
                    }
                }

                $this->current_file_id = $newPath;
            }

            if ($this->file_en) {
                $original = pathinfo($this->file_en->getClientOriginalName(), PATHINFO_FILENAME);
                $ext      = $this->file_en->getClientOriginalExtension();
                $clean    = \Illuminate\Support\Str::slug($original) . '-' . time() . '.' . $ext;

                $newPath = $this->file_en->storeAs('agenda/files', $clean, 'public');
                $data['file_en'] = $newPath;

                if ($this->current_file_en && Storage::disk('public')->exists($this->current_file_en)) {
                    try {
                        Storage::disk('public')->delete($this->current_file_en);
                    } catch (\Exception $e) {
                    }
                }

                $this->current_file_en = $newPath;
            }
        }


        DB::table('agenda')->where('id', (int) $this->id)->update($data);

        session()->flash('success', 'Agenda berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.cms.edit-agenda');
    }
}
