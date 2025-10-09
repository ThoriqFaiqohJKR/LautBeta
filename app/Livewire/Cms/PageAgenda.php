<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PageAgenda extends Component
{
    public string $section = 'event';
    public string $lang    = 'en';

    public bool $editMode = false;
    public ?string $editingSection = null;
    public ?int $editingId = null;

    public array $event = [
        'titleEN'       => '',
        'titleID'       => '',
        'descriptionEN' => '',
        'descriptionID' => '',
        'image'         => '',
    ];
    public ?string $eventImagePath = null;

    public array $activity = [
        'titleEN'       => '',
        'titleID'       => '',
        'descriptionEN' => '',
        'descriptionID' => '',
        'image'         => '',
    ];
    public ?string $activityImagePath = null;

    public $eventsList   = [];
    public $activitysList = [];

    public function mount(): void
    {
        $this->reloadLists();
    }

    public function setSection(string $sec): void
    {
        $this->section = in_array($sec, ['event', 'activity'], true) ? $sec : 'event';
    }

    public function setLang(string $l): void
    {
        $this->lang = in_array($l, ['en', 'id'], true) ? $l : 'en';
    }

    public function saveEvent(): void
    {
        $this->validate($this->rulesForEvent());

        $path = $this->event['image'] !== '' ? $this->event['image'] : null;

        DB::table('events')->insert([
            'title_en'       => $this->event['titleEN'] ?: null,
            'title_id'       => $this->event['titleID'] ?: null,
            'description_en' => $this->event['descriptionEN'] ?: null,
            'description_id' => $this->event['descriptionID'] ?: null,
            'image'          => $path,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $this->resetEventForm();
        session()->flash('successEvent', 'Event tersimpan.');
        $this->js('if (window.tinymce && tinymce.get("event_desc")) tinymce.get("event_desc").setContent("");');
        $this->reloadLists();
    }

    public function editEvent(int $id): void
    {
        $row = DB::table('events')->find($id);
        if (!$row) return;

        $this->editingId = $id;
        $this->editingSection = 'event';
        $this->editMode = true;

        $this->event['titleEN']       = $row->title_en ?? '';
        $this->event['titleID']       = $row->title_id ?? '';
        $this->event['descriptionEN'] = $row->description_en ?? '';
        $this->event['descriptionID'] = $row->description_id ?? '';
        $this->event['image']         = (string)($row->image ?? '');

        $this->eventImagePath = $this->publicUrl($row->image);
    }

    public function updateEvent(): void
    {
        if (!$this->editMode || $this->editingSection !== 'event' || !$this->editingId) return;

        $this->validate($this->rulesForEvent());

        $row = DB::table('events')->find($this->editingId);
        if (!$row) return;

        $path = $this->event['image'] !== '' ? $this->event['image'] : $row->image;

        DB::table('events')->where('id', $this->editingId)->update([
            'title_en'       => $this->event['titleEN'] ?: null,
            'title_id'       => $this->event['titleID'] ?: null,
            'description_en' => $this->event['descriptionEN'] ?: null,
            'description_id' => $this->event['descriptionID'] ?: null,
            'image'          => $path,
            'updated_at'     => now(),
        ]);

        session()->flash('successEvent', 'Event updated.');
        $this->cancelEdit();
        $this->reloadLists();
    }

    public function saveActivity(): void
    {
        $this->validate($this->rulesForActivity());

        $path = $this->activity['image'] !== '' ? $this->activity['image'] : null;

        DB::table('activitys')->insert([
            'title_en'       => $this->activity['titleEN'] ?: null,
            'title_id'       => $this->activity['titleID'] ?: null,
            'description_en' => $this->activity['descriptionEN'] ?: null,
            'description_id' => $this->activity['descriptionID'] ?: null,
            'image'          => $path,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $this->resetActivityForm();
        session()->flash('successActivity', 'Activity tersimpan.');
        $this->js('if (window.tinymce && tinymce.get("activity_desc")) tinymce.get("activity_desc").setContent("");');
        $this->reloadLists();
    }

    public function editActivity(int $id): void
    {
        $row = DB::table('activitys')->find($id);
        if (!$row) return;

        $this->editingId = $id;
        $this->editingSection = 'activity';
        $this->editMode = true;

        $this->activity['titleEN']       = $row->title_en ?? '';
        $this->activity['titleID']       = $row->title_id ?? '';
        $this->activity['descriptionEN'] = $row->description_en ?? '';
        $this->activity['descriptionID'] = $row->description_id ?? '';
        $this->activity['image']         = (string)($row->image ?? '');

        $this->activityImagePath = $this->publicUrl($row->image);
    }

    public function updateActivity(): void
    {
        if (!$this->editMode || $this->editingSection !== 'activity' || !$this->editingId) return;

        $this->validate($this->rulesForActivity());

        $row = DB::table('activitys')->find($this->editingId);
        if (!$row) return;

        $path = $this->activity['image'] !== '' ? $this->activity['image'] : $row->image;

        DB::table('activitys')->where('id', $this->editingId)->update([
            'title_en'       => $this->activity['titleEN'] ?: null,
            'title_id'       => $this->activity['titleID'] ?: null,
            'description_en' => $this->activity['descriptionEN'] ?: null,
            'description_id' => $this->activity['descriptionID'] ?: null,
            'image'          => $path,
            'updated_at'     => now(),
        ]);

        session()->flash('successActivity', 'Activity updated.');
        $this->cancelEdit();
        $this->reloadLists();
    }

    public function cancelEdit(): void
    {
        $this->editMode = false;
        $this->editingSection = null;
        $this->editingId = null;
        $this->resetEventForm();
        $this->resetActivityForm();
    }

    private function reloadLists(): void
    {
        $this->eventsList    = DB::table('events')->orderByDesc('id')->get();
        $this->activitysList = DB::table('activitys')->orderByDesc('id')->get();
    }

    private function publicUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (preg_match('~^https?://~i', $path)) return $path;
        return Storage::url($path);
    }

    private function resetEventForm(): void
    {
        $this->event = [
            'titleEN'       => '',
            'titleID'       => '',
            'descriptionEN' => '',
            'descriptionID' => '',
            'image'         => '',
        ];
        $this->eventImagePath = null;
    }

    private function resetActivityForm(): void
    {
        $this->activity = [
            'titleEN'       => '',
            'titleID'       => '',
            'descriptionEN' => '',
            'descriptionID' => '',
            'image'         => '',
        ];
        $this->activityImagePath = null;
    }

    private function rulesForEvent(): array
    {
        return [
            'event.titleEN'       => $this->lang === 'en' ? 'required|string' : 'required|string',
            'event.titleID'       => $this->lang === 'id' ? 'required|string' : 'required|string',
            'event.descriptionEN' => $this->lang === 'en' ? 'required|string' : 'required|string',
            'event.descriptionID' => $this->lang === 'id' ? 'required|string' : 'required|string',
            'event.image'         => 'required|string',
        ];
    }

    private function rulesForActivity(): array
    {
        return [
            'activity.titleEN'       => $this->lang === 'en' ? 'required|string' : 'required|string',
            'activity.titleID'       => $this->lang === 'id' ? 'required|string' : 'required|string',
            'activity.descriptionEN' => $this->lang === 'en' ? 'required|string' : 'required|string',
            'activity.descriptionID' => $this->lang === 'id' ? 'required|string' : 'required|string',
            'activity.image'         => 'required|string',
        ];
    }

    public function render()
    {
        return view('livewire.cms.page-agenda');
    }
}
