{{-- resources/views/livewire/cms/page-insight.blade.php --}}
@php($imageMode = $imageMode ?? 'upload')

<div class="max-w-4xl mx-auto p-12">
    <div class="bg-white p-12">
        <h2 class="text-2xl font-semibold mb-4">Insight</h2> 

         
        {{-- Switch Type (Feature/Analysis) --}}
        <div class="flex gap-2 mb-4">
            <button type="button"
                class="px-3 py-1 rounded border {{ $type==='feature' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}"
                wire:click="setType('feature')">Feature</button>
            <button type="button"
                class="px-3 py-1 rounded border {{ $type==='analysis' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}"
                wire:click="setType('analysis')">Analysis</button>
        </div>

        {{-- Switch Language (EN/ID) --}}
        <div class="flex gap-2 mb-6">
            <button type="button"
                class="px-3 py-1 rounded border {{ $lang==='en' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-300' }}"
                wire:click="setLang('en')">EN</button>
            <button type="button"
                class="px-3 py-1 rounded border {{ $lang==='id' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-300' }}"
                wire:click="setLang('id')">ID</button>
        </div>

        {{-- ================= FEATURE FORM ================= --}}
        @if($type === 'feature')
        <div class="space-y-5">
            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium">Judul Feature ({{ strtoupper($lang) }})</label>
                @if($lang==='en')
                <input type="text" wire:model.defer="featureTitleEN" class="w-full border rounded p-2">
                @error('featureTitleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                @else
                <input type="text" wire:model.defer="featureTitleID" class="w-full border rounded p-2">
                @error('featureTitleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                @endif
            </div>

            {{-- Description (TinyMCE + LFM) --}}
            <div>
                <label class="block text-sm font-medium">Deskripsi Feature ({{ strtoupper($lang) }})</label>
                <div wire:ignore
                    wire:key="feature-desc-{{ $lang }}"
                    x-data
                    data-base="{{ asset('tinymce') }}"
                    data-initial="{{ $lang==='id' ? ($featureDescriptionID ?? '') : ($featureDescriptionEN ?? '') }}"
                    x-init="
             const base = $el.dataset.base;
             const initial = $el.dataset.initial || '';
             if (window.tinymce && tinymce.get('feature_desc')) tinymce.get('feature_desc').remove();
             const lfmOpen = (type='image', prefix='/laravel-filemanager', onPick=()=>{}) => {
               const route = `${prefix}?type=${type}`;
               const fm = window.open(route, 'FileManager', 'width=900,height=600');
               window.SetUrl = function (items) {
                 try {
                   const arr = Array.isArray(items) ? items : [items];
                   const file = arr[0] || {};
                   onPick(file);
                 } finally {
                   if (fm && !fm.closed) fm.close();
                   window.SetUrl = null;
                 }
               };
             };
             tinymce.init({
               selector: '#feature_desc',
               height: 360,
               base_url: base,
               suffix: '.min',
               license_key: 'gpl',
               plugins: 'lists link image table',
               toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image table',
               menubar: 'file edit view insert format',
               branding: false,
               statusbar: true,
               elementpath: false,
               resize: true,
               forced_root_block: '',
               setup: (ed) => {
                 ed.on('init', () => { ed.setContent(initial) });
                 ed.on('change keyup undo redo input', () => {
                   const html = ed.getContent();
                   if (@this.get('lang') === 'id') {
                     @this.set('featureDescriptionID', html);
                   } else {
                     @this.set('featureDescriptionEN', html);
                   }
                 });
               },
            file_picker_types: 'image',
file_picker_callback: (cb, value, meta) => {
  if (meta.filetype !== 'image') return;
  const url = '/laravel-filemanager?type=image';

  tinymce.activeEditor.windowManager.openUrl({
    title: 'File Manager',
    url,
    width: 980,
    height: 600,

    // LFM v2.7+ kirim postMessage { mceAction:'fileSelected', data:{ file:{ url, name } } }
    onMessage: (api, message) => {
      const msg = message || {};
      const d = msg.data || {};
      if (msg.mceAction === 'fileSelected' || d.mceAction === 'fileSelected') {
        const file = d.file || (d.files && d.files[0]) || {};
        if (file.url) {
          cb(file.url, { alt: file.name || '' });
          api.close();
        }
      }
    }
  });
}

             });
           ">
                    <textarea id="feature_desc"></textarea>
                </div>
                @error('featureDescriptionEN') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                @error('featureDescriptionID') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Image (Upload/URL) --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium">Image Feature</label>
                <div class="flex gap-2">
                    <button type="button"
                        class="px-3 py-1 border rounded {{ $imageMode==='upload' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}"
                        wire:click="$set('imageMode','upload')">Upload File</button>
                    <button type="button"
                        class="px-3 py-1 border rounded {{ $imageMode==='url' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}"
                        wire:click="$set('imageMode','url')">Tempel URL</button>
                </div>
                @if($imageMode==='upload')
                <input type="file" accept="image/*" wire:model="featureImage" class="w-full border rounded p-2">
                <div wire:loading.flex wire:target="featureImage" class="text-sm text-slate-500 mt-1">Uploading...</div>
                @error('featureImage') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                @if($featureImage && method_exists($featureImage,'temporaryUrl'))
                <div class="mt-2"><img src="{{ $featureImage->temporaryUrl() }}" alt="Preview" class="max-h-48 rounded border"></div>
                @elseif($selectedInsight && $featureImagePath)
                <div class="mt-2"><img src="{{ $featureImagePath }}" alt="Current" class="max-h-48 rounded border"></div>
                @endif
                @else
                <input type="text" placeholder="https://…" wire:model.defer="featureImageUrl" class="w-full border rounded p-2">
                @error('featureImageUrl') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                @if(!empty($featureImageUrl))
                <div class="mt-2"><img src="{{ $featureImageUrl }}" alt="Preview URL" class="max-h-48 rounded border"></div>
                @endif
                <p class="text-xs text-slate-500">Buka File Manager di tab baru, pilih gambar, klik <b>Confirm</b>, salin URL, lalu tempel di sini:
                    <a href="{{ url('/laravel-filemanager?type=image') }}" target="_blank" class="text-emerald-700 underline">Buka File Manager</a>
                </p>
                @endif
            </div>
        </div>
        @endif

        {{-- ================= ANALYSIS FORM ================= --}}
        @if($type === 'analysis')
        <div class="space-y-5">
            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium">Judul Analysis ({{ strtoupper($lang) }})</label>
                @if($lang==='en')
                <input type="text" wire:model.defer="analysisTitleEN" class="w-full border rounded p-2">
                @error('analysisTitleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                @else
                <input type="text" wire:model.defer="analysisTitleID" class="w-full border rounded p-2">
                @error('analysisTitleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                @endif
            </div>

            {{-- Description (TinyMCE + LFM) --}}
            <div>
                <label class="block text-sm font-medium">Deskripsi Analysis ({{ strtoupper($lang) }})</label>
                <div wire:ignore
                    wire:key="analysis-desc-{{ $lang }}"
                    x-data
                    data-base="{{ asset('tinymce') }}"
                    data-initial="{{ $lang==='id' ? ($analysisDescriptionID ?? '') : ($analysisDescriptionEN ?? '') }}"
                    x-init="
             const base = $el.dataset.base;
             const initial = $el.dataset.initial || '';
             if (window.tinymce && tinymce.get('analysis_desc')) tinymce.get('analysis_desc').remove();
             const lfmOpen = (type='image', prefix='/laravel-filemanager', onPick=()=>{}) => {
               const route = `${prefix}?type=${type}`;
               const fm = window.open(route, 'FileManager', 'width=900,height=600');
               window.SetUrl = function (items) {
                 try {
                   const arr = Array.isArray(items) ? items : [items];
                   const file = arr[0] || {};
                   onPick(file);
                 } finally {
                   if (fm && !fm.closed) fm.close();
                   window.SetUrl = null;
                 }
               };
             };
             tinymce.init({
               selector: '#analysis_desc',
               height: 360,
               base_url: base,
               suffix: '.min',
               license_key: 'gpl',
               plugins: 'lists link image table',
               toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image table',
               menubar: 'file edit view insert format',
               branding: false,
               statusbar: true,
               elementpath: false,
               resize: true,
               forced_root_block: '',
               setup: (ed) => {
                 ed.on('init', () => { ed.setContent(initial) });
                 ed.on('change keyup undo redo input', () => {
                   const html = ed.getContent();
                   if (@this.get('lang') === 'id') {
                     @this.set('analysisDescriptionID', html);
                   } else {
                     @this.set('analysisDescriptionEN', html);
                   }
                 });
               },
               file_picker_types: 'image',
               file_picker_callback: (cb, value, meta) => {
                 if (meta.filetype !== 'image') return;
                 lfmOpen('image', '/laravel-filemanager', (file) => {
                   const url = file.url || '';
                   const alt = file.name || '';
                   if (url) cb(url, { alt });
                 });
               }
             });
           ">
                    <textarea id="analysis_desc"></textarea>
                </div>
                @error('analysisDescriptionEN') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                @error('analysisDescriptionID') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Image (Upload/URL) --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium">Image Analysis</label>
                <div class="flex gap-2">
                    <button type="button"
                        class="px-3 py-1 border rounded {{ $imageMode==='upload' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}"
                        wire:click="$set('imageMode','upload')">Upload File</button>
                    <button type="button"
                        class="px-3 py-1 border rounded {{ $imageMode==='url' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}"
                        wire:click="$set('imageMode','url')">Tempel URL</button>
                </div>
                @if($imageMode==='upload')
                <input type="file" accept="image/*" wire:model="analysisImage" class="w-full border rounded p-2">
                <div wire:loading.flex wire:target="analysisImage" class="text-sm text-slate-500 mt-1">Uploading...</div>
                @error('analysisImage') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                @if($analysisImage && method_exists($analysisImage,'temporaryUrl'))
                <div class="mt-2"><img src="{{ $analysisImage->temporaryUrl() }}" alt="Preview" class="max-h-48 rounded border"></div>
                @elseif($selectedInsight && $analysisImagePath)
                <div class="mt-2"><img src="{{ $analysisImagePath }}" alt="Current" class="max-h-48 rounded border"></div>
                @endif
                @else
                <input type="text" placeholder="https://…" wire:model.defer="analysisImageUrl" class="w-full border rounded p-2">
                @error('analysisImageUrl') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                @if(!empty($analysisImageUrl))
                <div class="mt-2"><img src="{{ $analysisImageUrl }}" alt="Preview URL" class="max-h-48 rounded border"></div>
                @endif
                <p class="text-xs text-slate-500">Buka File Manager di tab baru, pilih gambar, klik <b>Confirm</b>, salin URL, lalu tempel di sini:
                    <a href="{{ url('/laravel-filemanager?type=image') }}" target="_blank" class="text-emerald-700 underline">Buka File Manager</a>
                </p>
                @endif
            </div>
        </div>
        @endif

        {{-- ACTIONS --}}
        <div class="mt-4">
            @if($editMode)
            <div class="flex items-center gap-2">
                <button type="button" wire:click="update" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700">Update</button>
                <button type="button" wire:click="setType('{{ $type }}')" class="px-4 py-2 bg-slate-200 hover:bg-slate-300">Batal</button>
            </div>
            @else
            <button type="button" wire:click="save" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
            @endif
        </div>

        {{-- Flash --}}
        @if (session()->has('success'))
        <div class="p-3 bg-green-100 text-green-700 rounded mt-3">{{ session('success') }}</div>
        @endif

        {{-- LISTS --}}
        <div class="mt-6">
            <label class="block text-sm font-medium mb-1">Daftar Insight</label>

            {{-- Feature list --}}
            @if($type==='feature')
            <div class="grid grid-cols-1 gap-4">
                @foreach($features as $feature)
                <div class="flex items-start gap-4 border rounded p-3 cursor-pointer hover:bg-slate-50"
                    wire:click="$set('selectedInsight', {{ $feature->id }})">
                    <img src="{{ $feature->image_path ? Storage::url($feature->image_path) : ($feature->image ? Storage::url($feature->image) : asset('images/placeholder-80.svg')) }}"
                        class="w-20 h-20 object-cover rounded border bg-slate-100"
                        alt="{{ $feature->title_en ?? $feature->title_id }}" loading="lazy">
                    <div>
                        <h3 class="font-semibold">{{ $feature->title_en ?? ($feature->title_id ?? 'Tanpa Judul') }}</h3>
                        <p class="text-sm text-slate-600 line-clamp-2">{!! $feature->description_en ?? $feature->description_id !!}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Analysis list --}}
            @if($type==='analysis')
            <div class="grid grid-cols-1 gap-4">
                @foreach($analyses as $analysis)
                <div class="flex items-start gap-4 border rounded p-3 cursor-pointer hover:bg-slate-50"
                    wire:click="$set('selectedInsight', {{ $analysis->id }})">
                    <img src="{{ $analysis->image_path ? Storage::url($analysis->image_path) : ($analysis->image ? Storage::url($analysis->image) : asset('images/placeholder-80.svg')) }}"
                        class="w-20 h-20 object-cover rounded border bg-slate-100"
                        alt="{{ $analysis->title_en ?? $analysis->title_id }}" loading="lazy">
                    <div>
                        <h3 class="font-semibold">{{ $analysis->title_en ?? ($analysis->title_id ?? 'Tanpa Judul') }}</h3>
                        <p class="text-sm text-slate-600 line-clamp-2">{!! $analysis->description_en ?? $analysis->description_id !!}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
         

    </div>
</div>