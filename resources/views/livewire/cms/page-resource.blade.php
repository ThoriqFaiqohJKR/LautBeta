<div>
    {{-- In work, do what you enjoy. --}}

    <div class="max-w-4xl mx-auto p-12">
        <div class="bg-white p-12">
            <h2 class="text-2xl font-semibold mb-4">Resource</h2>


            <div class="flex gap-2 mb-4">
                <button type="button"
                    class="px-3 py-1 rounded border {{ $section==='report' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}"
                    wire:click="setSection('report')">Report</button>

                <button type="button"
                    class="px-3 py-1 rounded border {{ $section==='database' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}"
                    wire:click="setSection('database')">Database</button>

                <button type="button"
                    class="px-3 py-1 rounded border {{ $section==='gallery' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}"
                    wire:click="setSection('gallery')">Gallery</button>
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

            {{-- ================= FORM: REPORT ================= --}}
            @if($section==='report')
            <div class="space-y-5">
                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium">Judul Report ({{ strtoupper($lang) }})</label>
                    @if($lang==='en')
                    <input type="text" wire:model.defer="report.titleEN" class="w-full border rounded p-2">
                    @error('report.titleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @else
                    <input type="text" wire:model.defer="report.titleID" class="w-full border rounded p-2">
                    @error('report.titleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium">Deskripsi Report ({{ strtoupper($lang) }})</label>
                    @if($lang==='en')
                    <textarea rows="8" wire:model.defer="report.descriptionEN" class="w-full border rounded p-2"></textarea>
                    @error('report.descriptionEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @else
                    <textarea rows="8" wire:model.defer="report.descriptionID" class="w-full border rounded p-2"></textarea>
                    @error('report.descriptionID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                {{-- Actions --}}
                <div class="mt-2">
                    @if($editMode && ($editingSection??null)==='report')
                    <button type="button" wire:click="updateReport" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                    <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Batal</button>
                    @else
                    <button type="button" wire:click="saveReport" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
                    @endif
                </div>

                {{-- Flash --}}
                @if (session()->has('successReport'))
                <div class="p-3 bg-green-100 text-green-700 rounded mt-3">{{ session('successReport') }}</div>
                @endif

                {{-- List Reports --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium mb-1">Daftar Report</label>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($reportsList as $item)
                        <div class="border rounded p-3 cursor-pointer hover:bg-slate-50"
                            wire:click="editReport({{ $item->id }})">
                            <h3 class="font-semibold">{{ $item->title_en ?? ($item->title_id ?? 'Tanpa Judul') }}</h3>
                            <p class="text-sm text-slate-600 line-clamp-2">{!! $item->description_en ?? $item->description_id !!}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- ================= FORM: DATABASE ================= --}}
            @if($section==='database')
            <div class="space-y-5">
                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium">Judul Database ({{ strtoupper($lang) }})</label>
                    @if($lang==='en')
                    <input type="text" wire:model.defer="database.titleEN" class="w-full border rounded p-2">
                    @error('database.titleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @else
                    <input type="text" wire:model.defer="database.titleID" class="w-full border rounded p-2">
                    @error('database.titleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium">Deskripsi Database ({{ strtoupper($lang) }})</label>
                    @if($lang==='en')
                    <textarea rows="8" wire:model.defer="database.descriptionEN" class="w-full border rounded p-2"></textarea>
                    @error('database.descriptionEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @else
                    <textarea rows="8" wire:model.defer="database.descriptionID" class="w-full border rounded p-2"></textarea>
                    @error('database.descriptionID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                {{-- Actions --}}
                <div class="mt-2">
                    @if($editMode && ($editingSection??null)==='database')
                    <button type="button" wire:click="updateDatabase" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                    <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Batal</button>
                    @else
                    <button type="button" wire:click="saveDatabase" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
                    @endif
                </div>

                {{-- Flash --}}
                @if (session()->has('successDatabase'))
                <div class="p-3 bg-green-100 text-green-700 rounded mt-3">{{ session('successDatabase') }}</div>
                @endif

                {{-- List Databases --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium mb-1">Daftar Database</label>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($databasesList as $item)
                        <div class="border rounded p-3 cursor-pointer hover:bg-slate-50"
                            wire:click="editDatabase({{ $item->id }})">
                            <h3 class="font-semibold">{{ $item->title_en ?? ($item->title_id ?? 'Tanpa Judul') }}</h3>
                            <p class="text-sm text-slate-600 line-clamp-2">{!! $item->description_en ?? $item->description_id !!}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif


            @if($section==='gallery')
            <style>
                [x-cloak] {
                    display: none
                }
            </style>
            <div class="space-y-5"
                x-data="{
        lfmOpen:false,
        lfmSrc:'',
        pick(type, onPick){
          this.lfmSrc = `/laravel-filemanager?type=${type==='image' ? 'image' : 'file'}`;
          this.lfmOpen = true;
          window.SetUrl = (items) => {
            try{
              const arr = Array.isArray(items) ? items : [items];
              const file = arr[0] || {};
              const link = file.url || '';
              if (onPick && link) onPick(link, file);
            } finally {
              this.lfmOpen = false;
              this.lfmSrc = '';
              window.SetUrl = null;
            }
          };
        }
     }">

                <div>
                    <label class="block text-sm font-medium">Pilih Kategori Database</label>
                    <select wire:model="gallery.database_id" class="w-full border rounded p-2">
                        <option value="">— pilih —</option>
                        @foreach($databaseOptions as $opt)
                        <option value="{{ $opt->id }}">{{ $opt->title_en ?? $opt->title_id ?? ('ID#'.$opt->id) }}</option>
                        @endforeach
                    </select>
                    @error('gallery.database_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  

                    <div>
                        <label class="block text-sm font-medium">Type</label>
                        <select wire:model="gallery.type" class="w-full border rounded p-2">
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                        </select>
                        @error('gallery.type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Path (URL / storage path)</label>
                    <div class="flex gap-2">
                        <input id="gallery_path" type="text" wire:model.defer="gallery.path"
                            class="w-full border rounded p-2" placeholder="Klik Browse untuk memilih file…">
                        <button type="button"
                            class="px-3 py-2 border rounded bg-slate-800 text-white hover:bg-slate-900"
                            @click="pick(@js($gallery['type'] ?? 'image'), (url)=>{ $wire.set('gallery.path', url) })">
                            Browse
                        </button>
                    </div>
                    @error('gallery.path') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                    @if(($gallery['type'] ?? 'image')==='image' && !empty($gallery['path']))
                    <img src="{{ $gallery['path'] }}" alt="Preview" class="mt-2 max-h-48 rounded border">
                    @endif
                    @if(($gallery['type'] ?? '')==='video' && !empty($gallery['path']))
                    <video src="{{ $gallery['path'] }}" controls class="mt-2 max-h-56 rounded border"></video>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium">Judul ({{ strtoupper($lang) }})</label>
                    @if($lang==='en')
                    <input type="text" wire:model.defer="gallery.titleEN" class="w-full border rounded p-2">
                    @error('gallery.titleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @else
                    <input type="text" wire:model.defer="gallery.titleID" class="w-full border rounded p-2">
                    @error('gallery.titleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium">Deskripsi ({{ strtoupper($lang) }})</label>
                    @if($lang==='en')
                    <textarea rows="8" wire:model.defer="gallery.descriptionEN" class="w-full border rounded p-2"></textarea>
                    @error('gallery.descriptionEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @else
                    <textarea rows="8" wire:model.defer="gallery.descriptionID" class="w-full border rounded p-2"></textarea>
                    @error('gallery.descriptionID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                <div class="mt-2">
                    @if($editMode && ($editingSection??null)==='gallery')
                    <button type="button" wire:click="updateGallery" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                    <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Batal</button>
                    @else
                    <button type="button" wire:click="saveGallery" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
                    @endif
                </div>

                @if (session()->has('successGallery'))
                <div class="p-3 bg-green-100 text-green-700 rounded mt-3">{{ session('successGallery') }}</div>
                @endif

                <div class="mt-6">
                    <label class="block text-sm font-medium mb-1">Daftar Gallery</label>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($gallerysList as $item)
                        <div class="border rounded p-3 cursor-pointer hover:bg-slate-50"
                            wire:click="editGallery({{ $item->id }})">
                            <div class="text-xs text-slate-500 mb-1">
                                Kategori:
                                @php $cat = $databaseOptions->firstWhere('id', $item->database_id ?? null); @endphp
                                <b>{{ $cat->title_en ?? ($cat->title_id ?? ('ID#'.($item->database_id ?? '-'))) }}</b>
                                <span class="ml-2 px-2 py-0.5 text-[11px] rounded border">{{ strtoupper($item->type ?? '') }}</span>
                            </div>
                            <h3 class="font-semibold">{{ $item->title_en ?? ($item->title_id ?? 'Tanpa Judul') }}</h3>
                            <p class="text-sm text-slate-600 line-clamp-2">{!! $item->description_en ?? $item->description_id !!}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div x-cloak x-show="lfmOpen" class="fixed inset-0 z-50 grid place-items-center">
                    <div class="absolute inset-0 bg-black/50" @click="lfmOpen=false; lfmSrc=''; window.SetUrl=null"></div>
                    <div class="relative w-[92vw] max-w-4xl bg-white rounded-xl shadow-xl overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-2 border-b">
                            <h4 class="font-semibold text-sm">File Manager</h4>
                            <button class="text-slate-600 hover:text-slate-900" @click="lfmOpen=false; lfmSrc=''; window.SetUrl=null">✕</button>
                        </div>
                        <iframe :src="lfmSrc" class="w-full h-[72vh]" frameborder="0"></iframe>
                    </div>
                </div>

            </div>
            @endif


        </div>
    </div>

</div>