<div class="max-w-4xl mx-auto p-8 sm:p-10">
    {{-- BREADCRUMB --}}
    <div class="gap-2 flex mb-4">
        <div id="topForm"></div>

        <a href="{{ route('cms.page.index.agenda', ['locale' => app()->getLocale()]) }}">
            <p class="text-xl hover:underline">Page Agenda</p>
        </a>
        <p> > </p>
        <p class="text-xl text-blue-700">
            Edit Agenda
        </p>
    </div>

    <div class="bg-white border p-6 sm:p-10">
        {{-- INFO TYPE & BAHASA --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
            {{-- TYPE (HANYA DITAMPILKAN, TIDAK BISA DIGANTI) --}}
            <div class="sm:border-r sm:pr-6">
                <p class="py-2 font-semibold">Tipe Agenda :</p>
                <p class="inline-flex items-center px-3 py-1 text-sm rounded border bg-slate-50 text-slate-700">
                    {{-- kalau mau lebih rapi, bisa mapping ke label --}}
                    {{ ucfirst($type) }}
                </p>
                </p>
            </div>

            {{-- PILIH BAHASA --}}
            <div class="sm:pl-6">
                <p class="py-2 font-semibold">Pilih Bahasa</p>
                <div class="flex gap-2 mb-2">
                    <button type="button"
                        class="px-3 py-1 border {{ $lang==='en' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                        wire:click="$set('lang','en')">
                        EN
                    </button>
                    <button type="button"
                        class="px-3 py-1 border {{ $lang==='id' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                        wire:click="$set('lang','id')">
                        ID
                    </button>
                </div>

            </div>
        </div>

        {{-- FORM BODY --}}
        <div class="space-y-6 mt-6">
            {{-- TITLE --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Title ({{ strtoupper($lang) }})
                </label>

                @if ($lang === 'en')
                <input
                    wire:key="title-input-en"
                    id="title_en"
                    name="title_en"
                    type="text"
                    autocomplete="off"
                    wire:model.defer="title_en"
                    class="w-full border p-2"
                    placeholder="English title">
                @error('title_en')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @else
                <input
                    wire:key="title-input-id"
                    id="title_id"
                    name="title_id"
                    type="text"
                    autocomplete="off"
                    wire:model.defer="title_id"
                    class="w-full border p-2"
                    placeholder="Judul Indonesia">
                @error('title_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @endif
            </div>

            {{-- DESCRIPTION (TINYMCE) --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Description ({{ strtoupper($lang) }})
                </label>

                <div
                    wire:ignore
                    wire:key="desc-{{ $lang }}"
                    x-data
                    data-base="{{ asset('tinymce') }}"
                    data-initial="{{ $lang==='id' ? ($description_id ?? '') : ($description_en ?? '') }}"
                    x-init="
                        const base = $el.dataset.base, initial = $el.dataset.initial || '';
                        if (window.tinymce && tinymce.get('desc_editor')) tinymce.get('desc_editor').remove();

                        tinymce.init({
                            selector: '#desc_editor',
                            height: 260,
                            min_height: 180,
                            max_height: 420,
                            base_url: base,
                            suffix: '.min',
                            license_key: 'gpl',
                            plugins: 'advlist anchor autolink link lists image media table code preview',
                            menubar: false,
                            toolbar: 'undo redo | italic bold | image media | bullist numlist | table',
                            toolbar_mode: 'sliding',
                            promotion: false,
                            branding: false,
                            statusbar: true,
                            elementpath: false,
                            resize: true,
                            forced_root_block: 'p',

                            setup: (ed) => {
                                ed.on('init', () => { ed.setContent(initial) });
                                ed.on('change keyup undo redo', () => {
                                    const html = ed.getContent();
                                    if (@this.get('lang') === 'id') {
                                        @this.set('description_id', html);
                                    } else {
                                        @this.set('description_en', html);
                                    }
                                });
                            },

                            file_picker_types: 'image',
                            file_picker_callback: (cb, value, meta) => {
                                if (meta.filetype !== 'image') return;
                                const routePrefix = '/laravel-filemanager?type=image';

                                const popup = window.open(routePrefix, 'LFM', 'width=980,height=600');
                                window.SetUrl = (items) => {
                                    const f = (Array.isArray(items) ? items : [items])[0] || {};
                                    if (f.url) cb(f.url, { alt: f.name || '' });
                                    try { popup.close(); } catch (e) {}
                                };
                            }
                        });
                    ">
                    <textarea id="desc_editor"></textarea>
                </div>

                @error('description_en')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
                @error('description_id')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- CONTENT (TINYMCE) --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Content ({{ strtoupper($lang) }})
                </label>

                <div
                    wire:ignore
                    wire:key="content-{{ $lang }}"
                    x-data
                    data-base="{{ asset('tinymce') }}"
                    data-initial="{{ $lang==='id' ? ($content_id ?? '') : ($content_en ?? '') }}"
                    x-init="
                        const base = $el.dataset.base, initial = $el.dataset.initial || '';
                        if (window.tinymce && tinymce.get('content_editor')) tinymce.get('content_editor').remove();

                        tinymce.init({
                            selector: '#content_editor',
                            height: 340,
                            min_height: 220,
                            max_height: 520,
                            base_url: base,
                            suffix: '.min',
                            license_key: 'gpl',
                            plugins: 'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
                            menubar: false,
                            toolbar: 'undo redo | italic bold | image media | bullist numlist | table ',
                            toolbar_mode: 'sliding',
                            block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
                            toolbar_sticky: true,
                            promotion: false,
                            branding: false,
                            statusbar: true,
                            elementpath: false,
                            resize: true,
                            forced_root_block: 'p',

                            setup: (ed) => {
                                ed.on('init', () => { ed.setContent(initial) });
                                ed.on('change keyup undo redo', () => {
                                    const html = ed.getContent();
                                    if (@this.get('lang') === 'id') {
                                        @this.set('content_id', html);
                                    } else {
                                        @this.set('content_en', html);
                                    }
                                });
                            },

                            file_picker_types: 'image',
                            file_picker_callback: (cb, value, meta) => {
                                if (meta.filetype !== 'image') return;
                                const routePrefix = '/laravel-filemanager?type=image';

                                const openPopup = (url, w = 980, h = 600) => {
                                    const sl = window.screenLeft ?? window.screenX;
                                    const st = window.screenTop ?? window.screenY;
                                    const ww = window.innerWidth || document.documentElement.clientWidth || screen.width;
                                    const wh = window.innerHeight || document.documentElement.clientHeight || screen.height;
                                    const zoom = ww / window.screen.availWidth;
                                    const left = (ww - w) / 2 / zoom + sl;
                                    const top  = (wh - h) / 2 / zoom + st;
                                    const features = [
                                        'toolbar=no','location=no','status=no','menubar=no',
                                        'scrollbars=yes','resizable=yes',
                                        `width=${w}`,`height=${h}`,`top=${top}`,`left=${left}`
                                    ].join(',');
                                    const win = window.open(url, 'LFM_Popup', features);
                                    if (win && win.focus) win.focus();
                                    return win;
                                };

                                const oldSetUrl = window.SetUrl;
                                let restored = false;
                                let popupRef = null;
                                let poll = null;

                                const restore = () => {
                                    if (restored) return; restored = true;
                                    if (poll) clearInterval(poll);
                                    if (oldSetUrl) {
                                        window.SetUrl = oldSetUrl;
                                    } else {
                                        try { delete window.SetUrl } catch (e) { window.SetUrl = undefined }
                                    }
                                    window.removeEventListener('message', onMessage, false);
                                };

                                const onMessage = (ev) => {
                                    const data = ev?.data || {};
                                    const action = data.mceAction || ev?.mceAction;
                                    if (action === 'fileSelected') {
                                        const f = data.file || (data.files && data.files[0]) || {};
                                        if (f && f.url) {
                                            cb(f.url, { alt: f.name || '' });
                                            try { popupRef && popupRef.close && popupRef.close(); } catch (e) {}
                                            restore();
                                        }
                                    }
                                };
                                window.addEventListener('message', onMessage, false);

                                window.SetUrl = (items) => {
                                    try {
                                        const arr = Array.isArray(items) ? items : (items ? [items] : []);
                                        const f = arr[0] || {};
                                        if (f.url) cb(f.url, { alt: f.name || '' });
                                    } finally {
                                        try { popupRef && popupRef.close && popupRef.close(); } catch (e) {}
                                        restore();
                                    }
                                };

                                popupRef = openPopup(routePrefix, 980, 600);
                                poll = setInterval(() => {
                                    if (!popupRef || popupRef.closed) { restore(); }
                                }, 700);
                                setTimeout(() => restore(), 180000);
                            },

                            init_instance_callback: (editor) => {
                                const handle = editor.getContainer().querySelector('.tox-statusbar__resize-handle');
                                if (handle) {
                                    handle.style.marginLeft = 'auto';
                                    handle.style.marginRight = '4px';
                                    handle.style.cursor = 'se-resize';
                                }
                            }
                        });

                        window.addEventListener('set-content', (e) => {
                            const ed = tinymce.get('content_editor'); if (ed) ed.setContent(e.detail?.content || '');
                        });
                    ">
                    <textarea id="content_editor"></textarea>
                </div>

                @error('content_en')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
                @error('content_id')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- TANGGAL, STATUS, PUBLIKASI --}}
            <div class="grid sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Publikasi</label>
                    <input type="date" wire:model.defer="tanggal_publikasi" class="w-full border p-2">
                    @error('tanggal_publikasi')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Publikasi</label>
                    <select wire:model.defer="publikasi" class="w-full border p-2">
                        <option value="draf">Draf</option>
                        <option value="publish">Publish</option>
                    </select>
                    @error('publikasi')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select wire:model.defer="status" class="w-full border p-2">
                        <option value="on">On</option>
                        <option value="off">Off</option>
                    </select>
                    @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- GAMBAR --}}
            <div>
                <label class="block text-sm font-medium mb-1">Gambar</label>
                <input type="file" accept="image/*" wire:model="image" class="w-full border p-2">
                <div wire:loading wire:target="image" class="text-sm text-slate-500 mt-1">Uploading…</div>
                @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if ($image && method_exists($image,'temporaryUrl'))
                <div class="mt-3">
                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="max-h-52 border">
                </div>
                @elseif ($imagePreview)
                <div class="mt-3">
                    <img src="{{ $imagePreview }}" alt="Preview" class="max-h-52 border">
                </div>
                @endif
            </div>

            {{-- FILE UPLOAD UNTUK ACTIVITY --}}
            @if ($type === 'activity')
            <div class="mt-6">
                <label class="block text-sm font-medium mb-2">
                    File ({{ strtoupper($lang) }}) — PDF/DOC/DOCX
                </label>

                {{-- INPUT UNTUK BAHASA INDONESIA --}}
                @if ($lang === 'id')
                <input
                    type="file"
                    wire:model="file_id"
                    wire:key="file_id_input_edit"
                    accept=".pdf,.doc,.docx"
                    class="w-full border p-2">

                {{-- TAMPILKAN FILE EXISTING --}}
                @if (!empty($current_file_id))
                <p class="mt-2 text-sm">
                    File saat ini:
                    <a href="{{ Storage::url($current_file_id) }}" target="_blank"
                        class="text-blue-600 underline">
                        {{ basename($current_file_id) }} {{-- Nama file saja --}}
                    </a>
                </p>
                @endif


                @error('file_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                @else
                {{-- INPUT UNTUK BAHASA INGGRIS --}}
                <input
                    type="file"
                    wire:model="file_en"
                    wire:key="file_en_input_edit"
                    accept=".pdf,.doc,.docx"
                    class="w-full border p-2">

                {{-- TAMPILKAN FILE EXISTING --}}
                @if (!empty($current_file_en))
                <p class="mt-2 text-sm">
                    File saat ini:
                    <a href="{{ Storage::url($current_file_en) }}" target="_blank"
                        class="text-blue-600 underline">
                        {{ basename($current_file_en) }}
                    </a>
                </p>
                @endif


                @error('file_en')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @endif
            </div>
            @endif



            <div class="pt-2">
                <button type="button"
                    wire:click="update"
                    class="px-4 py-2 bg-green-600 text-white hover:bg-green-700">
                    Update
                </button>
            </div>

            {{-- FLASH MESSAGE --}}
            @if (session()->has('success'))
            <div class="p-3 bg-green-100 text-green-700">
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>
    <a href="#topForm"
        class="fixed bottom-6 right-6 bg-blue-600 text-white w-12 h-12 flex items-center justify-center rounded-full shadow-lg hover:bg-blue-700">


        <svg xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            viewBox="0 0 24 24"
            class="w-6 h-6">
            <path fill-rule="evenodd"
                d="M12 8.47l6.22 6.22a.75.75 0 101.06-1.06l-6.75-6.75a.75.75 0 00-1.06 0L4.72 13.63a.75.75 0 001.06 1.06L12 8.47z"
                clip-rule="evenodd" />
        </svg>

    </a>


</div>