<?php

namespace App\Livewire\Cms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class PageInsight extends Component
{
    use WithFileUploads; 

    public string $type = 'feature';
    public string $lang = 'en';
    public string $selectedInsight = '';
    public bool $editMode = false;

    public string $featureTitleEN = '';
    public string $featureTitleID = '';
    public string $featureDescriptionEN = ''; 
    public string $featureDescriptionID = '';

    public string $analysisTitleEN = '';
    public string $analysisTitleID = '';
    public string $analysisDescriptionEN = '';
    public string $analysisDescriptionID = '';

    public $featureImage = null;
    public $analysisImage = null;
    public ?string $featureImagePath = null;
    public ?string $analysisImagePath = null;

    public $features = [];
    public $analyses = [];

    private function tableName(): string
    {
        return $this->type === 'feature' ? 'feature' : 'analysis';
    }

    private function imageProperty(): string
    {
        return $this->type === 'feature' ? 'featureImage' : 'analysisImage';
    }

    private function imagePreviewProperty(): string
    {
        return $this->type === 'feature' ? 'featureImagePath' : 'analysisImagePath';
    }

    private function imageDir(): string
    {
        return $this->type === 'feature' ? 'insight/feature' : 'insight/analysis';
    }

    public function mount(): void
    {
        $this->reloadLists();
    }

    private function reloadLists(): void
    {
        $f = DB::table('feature')
            ->select('id', 'title_en', 'title_id', 'description_en', 'description_id');

        if (Schema::hasColumn('feature', 'image_path')) {
            $f->addSelect('image_path');
        } else {
            $f->addSelect(DB::raw('NULL as image_path'));
        }
        if (Schema::hasColumn('feature', 'image')) {
            $f->addSelect('image');
        } else {
            $f->addSelect(DB::raw('NULL as image'));
        }
        $this->features = $f->orderByDesc('id')->get();

        $a = DB::table('analysis')
            ->select('id', 'title_en', 'title_id', 'description_en', 'description_id');

        if (Schema::hasColumn('analysis', 'image_path')) {
            $a->addSelect('image_path');
        } else {
            $a->addSelect(DB::raw('NULL as image_path'));
        }
        if (Schema::hasColumn('analysis', 'image')) {
            $a->addSelect('image');
        } else {
            $a->addSelect(DB::raw('NULL as image'));
        }
        $this->analyses = $a->orderByDesc('id')->get();
    }


    public function setType(string $type): void
    {
        $this->type = in_array($type, ['feature', 'analysis'], true) ? $type : 'feature';
        $this->selectedInsight = '';
        $this->editMode = false;
        $this->resetForm();
        $this->clearImagesPreview();
        $this->dispatch('fill-editors', featureEN: '', featureID: '', analysisEN: '', analysisID: '');
    }

    public function setLang(string $lang): void
    {
        $this->lang = in_array($lang, ['en', 'id'], true) ? $lang : 'en';
    }

    public function updatedSelectedInsight($id): void
    {
        $this->resetForm();
        $this->editMode = false;
        $this->clearImagesPreview();

        if (!$id) {
            $this->dispatch('fill-editors', featureEN: '', featureID: '', analysisEN: '', analysisID: '');
            return;
        }

        if ($this->type === 'feature') {
            $row = collect($this->features)->firstWhere('id', (int)$id);
            if ($row) {
                $this->featureTitleEN       = $row->title_en ?? '';
                $this->featureTitleID       = $row->title_id ?? '';
                $this->featureDescriptionEN = $row->description_en ?? '';
                $this->featureDescriptionID = $row->description_id ?? '';
                $this->editMode = true;

                $this->dispatch(
                    'fill-editors',
                    featureEN: $this->featureDescriptionEN,
                    featureID: $this->featureDescriptionID,
                    analysisEN: '',
                    analysisID: ''
                );

                if (Schema::hasColumn('feature', 'image')) {
                    $path = DB::table('feature')->where('id', (int)$id)->value('image');
                    $this->featureImagePath = $path ? Storage::url($path) : null;
                }
            }
        } else {
            $row = collect($this->analyses)->firstWhere('id', (int)$id);
            if ($row) {
                $this->analysisTitleEN       = $row->title_en ?? '';
                $this->analysisTitleID       = $row->title_id ?? '';
                $this->analysisDescriptionEN = $row->description_en ?? '';
                $this->analysisDescriptionID = $row->description_id ?? '';
                $this->editMode = true;

                $this->dispatch(
                    'fill-editors',
                    featureEN: '',
                    featureID: '',
                    analysisEN: $this->analysisDescriptionEN,
                    analysisID: $this->analysisDescriptionID
                );

                if (Schema::hasColumn('analysis', 'image')) {
                    $path = DB::table('analysis')->where('id', (int)$id)->value('image');
                    $this->analysisImagePath = $path ? Storage::url($path) : null;
                }
            }
        }
    }

    protected function rules(): array
    {
        $fileRule = File::image()->max(5 * 1024);
        return [
            'featureTitleEN' => ['required', 'string', 'max:255'],
            'featureTitleID' => ['required', 'string', 'max:255'],
            'analysisTitleEN' => ['required', 'string', 'max:255'],
            'analysisTitleID' => ['required', 'string', 'max:255'],
            'featureImage' => ['required', $fileRule],
            'analysisImage' => ['required', $fileRule],
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->type === 'feature') {
            $data = [
                'title_en'       => $this->featureTitleEN,
                'title_id'       => $this->featureTitleID,
                'description_en' => $this->featureDescriptionEN,
                'description_id' => $this->featureDescriptionID,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];

            if ($this->featureImage && Schema::hasColumn('feature', 'image')) {
                $path = $this->featureImage->store($this->imageDir(), 'public');
                $data['image'] = $path;
                $this->featureImagePath = Storage::url($path);
            }

            DB::table('feature')->insert($data);
        } else {
            $data = [
                'title_en'       => $this->analysisTitleEN,
                'title_id'       => $this->analysisTitleID,
                'description_en' => $this->analysisDescriptionEN,
                'description_id' => $this->analysisDescriptionID,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];

            if ($this->analysisImage && Schema::hasColumn('analysis', 'image')) {
                $path = $this->analysisImage->store($this->imageDir(), 'public');
                $data['image'] = $path;
                $this->analysisImagePath = Storage::url($path);
            }

            DB::table('analysis')->insert($data);
        }

        $this->afterWrite('Berhasil disimpan.');
    }

    public function update(): void
    {
        if (!$this->selectedInsight) return;

        $this->validate();

        if ($this->type === 'feature') {
            $data = [
                'title_en'       => $this->featureTitleEN,
                'title_id'       => $this->featureTitleID,
                'description_en' => $this->featureDescriptionEN,
                'description_id' => $this->featureDescriptionID,
                'updated_at'     => now(),
            ];

            if ($this->featureImage && Schema::hasColumn('feature', 'image')) {
                $path = $this->featureImage->store($this->imageDir(), 'public');
                $data['image'] = $path;
                $this->featureImagePath = Storage::url($path);
            }

            DB::table('feature')->where('id', $this->selectedInsight)->update($data);
        } else {
            $data = [
                'title_en'       => $this->analysisTitleEN,
                'title_id'       => $this->analysisTitleID,
                'description_en' => $this->analysisDescriptionEN,
                'description_id' => $this->analysisDescriptionID,
                'updated_at'     => now(),
            ];

            if ($this->analysisImage && Schema::hasColumn('analysis', 'image')) {
                $path = $this->analysisImage->store($this->imageDir(), 'public');
                $data['image'] = $path;
                $this->analysisImagePath = Storage::url($path);
            }

            DB::table('analysis')->where('id', $this->selectedInsight)->update($data);
        }

        $this->afterWrite('Berhasil diupdate.');
    }

    public function removeImage(): void
    {
        if (!$this->selectedInsight) return;

        $table = $this->tableName();
        if (!Schema::hasColumn($table, 'image')) {
            return;
        }

        $old = DB::table($table)->where('id', (int)$this->selectedInsight)->value('image');
        DB::table($table)->where('id', (int)$this->selectedInsight)->update(['image' => null, 'updated_at' => now()]);

        if ($old && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        if ($this->type === 'feature') {
            $this->featureImage = null;
            $this->featureImagePath = null;
        } else {
            $this->analysisImage = null;
            $this->analysisImagePath = null;
        }

        session()->flash('success', 'Gambar berhasil dihapus.');
    }

    private function afterWrite(string $msg): void
    {
        $this->reloadLists();
        $this->editMode = false;
        $this->selectedInsight = '';
        $this->resetForm();
        $this->clearImagesPreview();
        $this->dispatch('fill-editors', featureEN: '', featureID: '', analysisEN: '', analysisID: '');
        session()->flash('success', $msg);
    }

    private function resetForm(): void
    {
        $this->featureTitleEN = $this->featureTitleID = '';
        $this->featureDescriptionEN = $this->featureDescriptionID = '';
        $this->analysisTitleEN = $this->analysisTitleID = '';
        $this->analysisDescriptionEN = $this->analysisDescriptionID = '';
        $this->featureImage = null;
        $this->analysisImage = null;
    }

    private function clearImagesPreview(): void
    {
        $this->featureImagePath = null;
        $this->analysisImagePath = null;
    }

    public function render()
    {
        return view('livewire.cms.page-insight');
    }
}
