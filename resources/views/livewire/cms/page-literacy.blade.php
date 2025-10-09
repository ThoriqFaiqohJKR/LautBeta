<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <p>hapines</p>
    @php($imageMode = $imageMode ?? 'upload')
    @php($section = $section ?? 'reports') {{-- 'reports' | 'journal' --}}
    <div class="max-w-4xl mx-auto p-12">
        <div class="bg-white p-12">
            <h2 class="text-2xl font-semibold mb-4">Literacy</h2>

            {{-- Switch Section: Reports / Journal --}}
            <div class="flex gap-2 mb-4">
                <button type="button"
                    class="px-3 py-1 rounded border {{ $section==='reports' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}"
                    wire:click="setSection('reports')">Reports</button>
                <button type="button"
                    class="px-3 py-1 rounded border {{ $section==='journal' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}"
                    wire:click="setSection('journal')">Journal</button>
            </div>

            {{-- Switch Language --}}
            <div class="flex gap-2 mb-6">
                <button type="button"
                    class="px-3 py-1 rounded border {{ $lang==='en' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-300' }}"
                    wire:click="setLang('en')">EN</button>
                <button type="button"
                    class="px-3 py-1 rounded border {{ $lang==='id' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-300' }}"
                    wire:click="setLang('id')">ID</button>
            </div>

            {{-- ================= REPORTS ================= --}}
            @if($section==='reports')
            <div class="space-y-5">
                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium">Judul Reports ({{ strtoupper($lang) }})</label>
                    @if($lang==='en')
                    <input type="text" wire:model.defer="reports.titleEN" class="w-full border rounded p-2">
                    @error('reports.titleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @else
                    <input type="text" wire:model.defer="reports.titleID" class="w-full border rounded p-2">
                    @error('reports.titleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                {{-- Description --}}
                {{-- Deskripsi Reports (TinyMCE + LFM) --}}
                {{-- Listener sekali untuk auto-isi TinyMCE saat edit --}}
                <div x-data
                    x-init="
       window.addEventListener('tinymce-fill', (e) => {
         const d = e.detail || {};
         const ed = window.tinymce && tinymce.get(d.target);
         if (ed) ed.setContent(d.content ?? '');
       });
     ">

                    {{-- Deskripsi Reports (TinyMCE + LFM, auto-fill via browser event) --}}
                    <div>
                        <label class="block text-sm font-medium">Deskripsi Reports ({{ strtoupper($lang) }})</label>

                        <div wire:ignore
                            wire:key="reports-desc-{{ $lang }}"
                            x-data
                            data-base="{{ asset('tinymce') }}"
                            data-initial="{{ $lang==='id' ? ($reports['descriptionID'] ?? '') : ($reports['descriptionEN'] ?? '') }}"
                            x-init="
           const base = $el.dataset.base;
           const initial = $el.dataset.initial || '';

           // pastikan instance lama dibersihkan
           if (window.tinymce && tinymce.get('reports_desc')) tinymce.get('reports_desc').remove();

           tinymce.init({
             selector: '#reports_desc',
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
                   @this.set('reports.descriptionID', html);
                 } else {
                   @this.set('reports.descriptionEN', html);
                 }
               });
             },
             // LFM popup dalam modal TinyMCE
             file_picker_types: 'image',
             file_picker_callback: (cb, value, meta) => {
               if (meta.filetype !== 'image') return;
               tinymce.activeEditor.windowManager.openUrl({
                 title: 'File Manager',
                 url: '/laravel-filemanager?type=image',
                 width: 980,
                 height: 600,
                 onMessage: (api, message) => {
                   const msg = message || {};
                   const d = msg.data || {};
                   if (msg.mceAction === 'fileSelected' || d.mceAction === 'fileSelected') {
                     const f = d.file || (d.files && d.files[0]) || {};
                     if (f.url) { cb(f.url, { alt: f.name || '' }); api.close(); }
                   }
                 }
               });
             }
           });
         ">
                            <textarea id="reports_desc"></textarea>
                        </div>

                        @error('reports.descriptionEN') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        @error('reports.descriptionID') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                </div>



                {{-- Image (Upload / URL) --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Image Reports</label>

                    <div class="flex gap-2">
                        <button type="button"
                            class="px-3 py-1 border rounded {{ $imageMode==='upload' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}"
                            wire:click="$set('imageMode','upload')">Upload File</button>
                        <button type="button"
                            class="px-3 py-1 border rounded {{ $imageMode==='url' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700' }}"
                            wire:click="$set('imageMode','url')">Tempel URL</button>
                    </div>

                    @if($imageMode==='upload')
                    <input type="file" accept="image/*" wire:model="reports.image" class="w-full border rounded p-2">
                    <div wire:loading.flex wire:target="reports.image" class="text-sm text-slate-500 mt-1">Uploading...</div>
                    @error('reports.image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                    @if(data_get($reports,'image') && method_exists(data_get($reports,'image'),'temporaryUrl'))
                    <div class="mt-2"><img src="{{ data_get($reports,'image')->temporaryUrl() }}" alt="Preview" class="max-h-48 rounded border"></div>
                    @elseif(!empty($reportsImagePath))
                    <div class="mt-2"><img src="{{ $reportsImagePath }}" alt="Current" class="max-h-48 rounded border"></div>
                    @endif
                    @else
                    <input type="text" placeholder="https://…" wire:model.defer="reports.imageUrl" class="w-full border rounded p-2">
                    @error('reports.imageUrl') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @if(!empty(data_get($reports,'imageUrl')))
                    <div class="mt-2"><img src="{{ data_get($reports,'imageUrl') }}" alt="Preview URL" class="max-h-48 rounded border"></div>
                    @endif
                    @endif
                </div>

                {{-- Actions --}}
                <div class="mt-2">
                    @if($editMode && ($editingSection??null)==='reports')
                    <button type="button" wire:click="updateReports" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                    <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Batal</button>
                    @else
                    <button type="button" wire:click="saveReports" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
                    @endif
                </div>

                {{-- Flash --}}
                @if (session()->has('successReports'))
                <div class="p-3 bg-green-100 text-green-700 rounded mt-3">{{ session('successReports') }}</div>
                @endif

                {{-- List Reports --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium mb-1">Daftar Reports</label>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($reportsList as $item)
                        <div class="flex items-start gap-4 border rounded p-3 cursor-pointer hover:bg-slate-50"
                            wire:click="editReports({{ $item->id }})">
                            <img src="{{ $item->image ? Storage::url($item->image) : asset('images/placeholder-80.svg') }}"

                                class="w-20 h-20 object-cover rounded border bg-slate-100"
                                alt="{{ $item->title_en ?? $item->title_id }}" loading="lazy">
                            <div>
                                <h3 class="font-semibold">{{ $item->title_en ?? ($item->title_id ?? 'Tanpa Judul') }}</h3>
                                <p class="text-sm text-slate-600 line-clamp-2">{!! $item->description_en ?? $item->description_id !!}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- ================= JOURNAL ================= --}}
            @if($section==='journal')
            <div class="space-y-5">
                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium">Judul Journal ({{ strtoupper($lang) }})</label>
                    @if($lang==='en')
                    <input type="text" wire:model.defer="journal.titleEN" class="w-full border rounded p-2">
                    @error('journal.titleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @else
                    <input type="text" wire:model.defer="journal.titleID" class="w-full border rounded p-2">
                    @error('journal.titleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                {{-- Description --}}
                {{-- Deskripsi Journal (TinyMCE + LFM) --}}
                {{-- Listener sekali untuk auto-isi TinyMCE (taruh di wrapper terluar, kalau belum ada) --}}
                <div x-data
                    x-init="
       window.addEventListener('tinymce-fill', (e) => {
         const d = e.detail || {};
         const ed = window.tinymce && tinymce.get(d.target);
         if (ed) ed.setContent(d.content ?? '');
       });
     ">

                    {{-- Deskripsi Journal (TinyMCE + LFM, auto-fill via browser event) --}}
                    <div>
                        <label class="block text-sm font-medium">Deskripsi Journal ({{ strtoupper($lang) }})</label>

                        <div wire:ignore
                            wire:key="journal-desc-{{ $lang }}"
                            x-data
                            data-base="{{ asset('tinymce') }}"
                            data-initial="{{ $lang==='id' ? ($journal['descriptionID'] ?? '') : ($journal['descriptionEN'] ?? '') }}"
                            x-init="
           const base = $el.dataset.base;
           const initial = $el.dataset.initial || '';

           // pastikan instance lama dibersihkan
           if (window.tinymce && tinymce.get('journal_desc')) tinymce.get('journal_desc').remove();

           tinymce.init({
             selector: '#journal_desc',
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
                   @this.set('journal.descriptionID', html);
                 } else {
                   @this.set('journal.descriptionEN', html);
                 }
               });
             },
             // LFM popup dalam modal TinyMCE
             file_picker_types: 'image',
             file_picker_callback: (cb, value, meta) => {
               if (meta.filetype !== 'image') return;
               tinymce.activeEditor.windowManager.openUrl({
                 title: 'File Manager',
                 url: '/laravel-filemanager?type=image',
                 width: 980,
                 height: 600,
                 onMessage: (api, message) => {
                   const msg = message || {};
                   const d = msg.data || {};
                   if (msg.mceAction === 'fileSelected' || d.mceAction === 'fileSelected') {
                     const f = d.file || (d.files && d.files[0]) || {};
                     if (f.url) { cb(f.url, { alt: f.name || '' }); api.close(); }
                   }
                 }
               });
             }
           });
         ">
                            <textarea id="journal_desc"></textarea>
                        </div>

                        @error('journal.descriptionEN') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        @error('journal.descriptionID') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                </div>



                {{-- Image (optional) --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Thumbnail / Cover (opsional)</label>
                    <input type="file" accept="image/*" wire:model="journal.image" class="w-full border rounded p-2">
                    <div wire:loading.flex wire:target="journal.image" class="text-sm text-slate-500 mt-1">Uploading...</div>
                    @error('journal.image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                    @if(data_get($journal,'image') && method_exists(data_get($journal,'image'),'temporaryUrl'))
                    <div class="mt-2"><img src="{{ data_get($journal,'image')->temporaryUrl() }}" alt="Preview" class="max-h-48 rounded border"></div>
                    @elseif(!empty($journalImagePath))
                    <div class="mt-2"><img src="{{ $journalImagePath }}" alt="Current" class="max-h-48 rounded border"></div>
                    @endif
                </div>

                {{-- Attachment upload --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Lampiran (PDF/DOCX/XLSX/PPTX/ZIP, max 10MB)</label>
                    <input type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,image/*" wire:model="journal.attachment" class="w-full border rounded p-2">
                    <div wire:loading.flex wire:target="journal.attachment" class="text-sm text-slate-500 mt-1">Uploading...</div>
                    @error('journal.attachment') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                    @if(!empty($journalAttachmentName))
                    <p class="text-sm text-slate-700">File: {{ $journalAttachmentName }}</p>
                    @elseif(!empty($journalAttachmentUrl))
                    <p class="text-sm text-slate-700">File saat ini:
                        <a class="text-emerald-700 underline" href="{{ $journalAttachmentUrl }}" target="_blank">Download</a>
                    </p>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="mt-2">
                    @if($editMode && ($editingSection??null)==='journal')
                    <button type="button" wire:click="updateJournal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                    <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Batal</button>
                    @else
                    <button type="button" wire:click="saveJournal" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
                    @endif
                </div>

                {{-- Flash --}}
                @if (session()->has('successJournal'))
                <div class="p-3 bg-green-100 text-green-700 rounded mt-3">{{ session('successJournal') }}</div>
                @endif

                {{-- List Journal --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium mb-1">Daftar Journal</label>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($journalsList as $item)
                        <div class="flex items-start gap-4 border rounded p-3 cursor-pointer hover:bg-slate-50"
                            wire:click="editJournal({{ $item->id }})">
                            <img src="{{ $item->image ? Storage::url($item->image) : asset('images/placeholder-80.svg') }}"
                                class="w-20 h-20 object-cover rounded border bg-slate-100"
                                alt="{{ $item->title_en ?? $item->title_id }}" loading="lazy">
                            <div>
                                <h3 class="font-semibold">{{ $item->title_en ?? ($item->title_id ?? 'Tanpa Judul') }}</h3>
                                <p class="text-sm text-slate-600 line-clamp-2">{!! $item->description_en ?? $item->description_id !!}</p>
                                @if(!empty($item->file))
                                <p class="text-xs text-slate-500 mt-1">
                                    Lampiran:
                                    <a href="{{ Storage::url($item->file) }}" target="_blank">Download</a>
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>