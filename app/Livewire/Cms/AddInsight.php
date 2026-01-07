<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;

class AddInsight extends Component
{
    use WithFileUploads;

    private const TABLE = 'insight';

    public string $lang = 'en';
    public string $type = 'feature';

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

    protected $messages = [
        'title_en.required_without' => 'Minimal isi salah satu judul: EN atau ID.',
        'title_id.required_without' => 'Minimal isi salah satu judul: EN atau ID.',
    ];

    protected function rules(): array
    {
        return [
            'title_en'          => ['required', 'string', 'max:255', 'required_without:title_id'],
            'title_id'          => ['required', 'string', 'max:255', 'required_without:title_en'],
            'description_en'    => ['required', 'string'],
            'description_id'    => ['required', 'string'],
            'content_en'        => ['required', 'string'],
            'content_id'        => ['required', 'string'],
            'type'              => ['required', 'in:feature,analysis,ngopini'],
            'tanggal_publikasi' => ['required', 'date'],
            'publikasi'         => ['required', 'in:draf,publish'],
            'image'             => ['required', File::image()->max(5 * 1024)],
        ];
    }

    public function save()
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
            'status'            => 'on',
            'type'              => $this->type,
            'slug'              => Str::slug($this->title_id),
            'created_at'        => now(),
            'updated_at'        => now(),
        ];

        if ($this->image) {
            $path = $this->image->store("insight/{$this->type}", 'public');
            $data['image'] = $path;
            $this->imagePreview = Storage::url($path);
        }

        DB::table(self::TABLE)->insert($data);

        return redirect()
            ->route('cms.page.index.insight', ['locale' => app()->getLocale()])
            ->with('success', 'Insight berhasil disimpan.');
    }

    private function resetForm(): void
    {
        $this->lang = 'en';
        $this->type = 'feature';
        $this->title_en = $this->title_id = '';
        $this->description_en = $this->description_id = '';
        $this->content_en = $this->content_id = '';
        $this->tanggal_publikasi = null;
        $this->publikasi = 'draf';
        $this->status = 'on';
        $this->slug = null;
        $this->image = null;
        $this->imagePreview = null;
    }

    public function render()
    {
        return view('livewire.cms.add-insight');
    }
}
