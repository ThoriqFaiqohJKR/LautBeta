<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PreviewAgenda extends Component
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

    protected $queryString = [
        'lang' => ['except' => 'en'],
    ];

    public function mount(int $id): void
    {
        $this->id  = $id;
        $this->lang = in_array(request('lang'), ['en', 'id'], true) ? request('lang') : 'en';

        $row = DB::table('agenda')->where('id', $id)->first();
        if (!$row) abort(404);

        $this->type              = $row->type;
        $this->title_en          = $row->title_en;
        $this->title_id          = $row->title_id;
        $this->description_en    = $row->description_en;
        $this->description_id    = $row->description_id;
        $this->content_en        = $row->content_en;
        $this->content_id        = $row->content_id;
        $this->tanggal_publikasi = $row->tanggal_publikasi;
        $this->publikasi         = $row->publikasi;
        $this->status            = $row->status;
        $this->slug              = $row->slug;
        $this->image             = $row->image;

        if ($row->image && Storage::disk('public')->exists($row->image)) {
            $this->imagePreview = Storage::url($row->image);
        }
    }

    public function setLang(string $lang): void
    {
        if (!in_array($lang, ['en', 'id'], true)) return;
        $this->lang = $lang;
    }

    private function normalizeTinymceUrls(?string $html): ?string
    {
        if (!$html) return $html;

        return preg_replace_callback(
            '/\b(src|data-mce-src)\s*=\s*"([^"]+)"/i',
            function ($m) {
                $attr = $m[1];
                $src  = $m[2];

                if (preg_match('#^(https?:)?//#i', $src) || str_starts_with($src, 'data:')) {
                    return $m[0];
                }

                $path = ltrim($src, '/');
                $path = preg_replace('#^storage/#', '', $path);
                $path = preg_replace('#^public/#', '', $path);

                $url = Storage::url($path);
                return $attr . '="' . e($url) . '"';
            },
            $html
        );
    }

    public function getTitleProperty(): ?string
    {
        return $this->lang === 'id'
            ? ($this->title_id ?? $this->title_en)
            : ($this->title_en ?? $this->title_id);
    }

    public function getDescriptionProperty(): ?string
    {
        return $this->lang === 'id'
            ? ($this->description_id ?? $this->description_en)
            : ($this->description_en ?? $this->description_id);
    }

    public function getContentProperty(): ?string
    {
        return $this->lang === 'id'
            ? ($this->content_id ?? $this->content_en)
            : ($this->content_en ?? $this->content_id);
    }

    public function getDescriptionHtmlProperty(): ?string
    {
        return $this->normalizeTinymceUrls($this->description);
    }

    public function getContentHtmlProperty(): ?string
    {
        return $this->normalizeTinymceUrls($this->content);
    }

    public function render()
    {
        return view('livewire.cms.preview-agenda');
    }
}
