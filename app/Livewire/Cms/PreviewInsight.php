<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PreviewInsight extends Component
{
    public string $lang = 'en';

    public ?int $id = null;
    public ?string $type = null;
    public ?string $title_en = null;
    public ?string $title_id = null;
    public ?string $description_en = null;
    public ?string $description_id = null;
    public ?string $content_en = null;
    public ?string $content_id = null;
    public ?string $tanggal_publikasi = null;
    public ?string $publikasi = null;
    public ?string $status = null;
    public ?string $slug = null;
    public ?string $image = null;
    public ?string $imagePreview = null;

    public function mount($id): void
    {
        $this->lang = request()->route('locale') === 'id' ? 'id' : 'en';
        $this->id   = (int) $id;

        $row = DB::table('insight')->where('id', $this->id)->first();
        if (!$row) abort(404);

        $this->type              = $row->type ?? null;
        $this->title_en          = $row->title_en ?? null;
        $this->title_id          = $row->title_id ?? null;
        $this->description_en    = $row->description_en ?? null;
        $this->description_id    = $row->description_id ?? null;
        $this->content_en        = $row->content_en ?? null;
        $this->content_id        = $row->content_id ?? null;
        $this->tanggal_publikasi = $row->tanggal_publikasi ?? null;
        $this->publikasi         = $row->publikasi ?? null;
        $this->status            = $row->status ?? null;
        $this->slug              = $row->slug ?? null;
        $this->image             = $row->image ?? null;

        if ($this->image && Storage::disk('public')->exists($this->image)) {
            $this->imagePreview = Storage::url($this->image);
        }
    }

    private function fixImagePath(?string $html): string
    {
        if (!$html) return '';
        return preg_replace(
            '/src=["\'](\.\.\/)+storage/',
            'src="' . asset('storage'),
            $html
        );
    }

    public function getDisplayTitleProperty(): string
    {
        return $this->lang === 'id'
            ? ($this->title_id ?? $this->title_en ?? '-')
            : ($this->title_en ?? $this->title_id ?? '-');
    }

    public function getDisplayDescriptionProperty(): string
    {
        return $this->lang === 'id'
            ? ($this->description_id ?? $this->description_en ?? '')
            : ($this->description_en ?? $this->description_id ?? '');
    }

    public function getDisplayContentProperty(): string
    {
        $raw = $this->lang === 'id'
            ? ($this->content_id ?? $this->content_en ?? '')
            : ($this->content_en ?? $this->content_id ?? '');
        return $this->fixImagePath($raw);
    }

    public function switchLang(string $lang = 'en'): void
    {
        $this->lang = $lang === 'id' ? 'id' : 'en';
    }

    public function render()
    {
        return view('livewire.cms.preview-insight', [
            'title'       => $this->displayTitle,
            'description' => $this->displayDescription,
            'content'     => $this->displayContent,
        ]);
    }
}
