<div>
    {{-- Be like water. --}}
    <div>
        <div class="max-w-4xl mx-auto p-6 sm:p-10">
            <div class="gap-2 flex mb-4">
                <a href="{{ route('cms.page.index.resource', ['locale' => app()->getLocale()]) }}">
                    <p class="text-xl hover:underline">Page Resource </p>
                </a>
                <p> > </p>
                <p class="text-xl text-blue-700">Tambah Resource</p>
            </div>
            <div class="bg-white  border p-6 sm:p-10 space-y-6">
                <h2 class="text-2xl font-semibold">Add Resource</h2>

                <div class="flex gap-2">
                    <button type="button" class="px-3 py-1   border {{ $source==='report' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300' }}" wire:click="$set('source','report')">Report</button>
                    <button type="button" class="px-3 py-1   border {{ $source==='database' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300' }}" wire:click="$set('source','database')">Database</button>
                    <button type="button" class="px-3 py-1   border {{ $source==='gallery' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300' }}" wire:click="$set('source','gallery')">Gallery</button>
                </div>

                <div class="flex gap-2">
                    <button type="button" class="px-3 py-1   border {{ $lang==='en' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}" wire:click="$set('lang','en')">EN</button>
                    <button type="button" class="px-3 py-1   border {{ $lang==='id' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}" wire:click="$set('lang','id')">ID</button>
                </div>

                <div x-data>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Title ({{ strtoupper($lang) }})</label>
                    <input type="text" class="w-full border   p-2" wire:model.defer="title_en" wire:key="title-en" x-show="$wire.get('lang')==='en'">
                    <input type="text" class="w-full border   p-2" wire:model.defer="title_id" wire:key="title-id" x-show="$wire.get('lang')==='id'">
                    @if($lang==='en')
                    @error('title_en') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @else
                    @error('title_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Description ({{ strtoupper($lang) }})</label>
                    <div wire:ignore wire:key="desc-editor" x-data data-base="{{ asset('tinymce') }}" data-initial="{{ $lang==='id' ? ($description_id ?? '') : ($description_en ?? '') }}" x-init="
          const base=$el.dataset.base; const initial=$el.dataset.initial||'';
          if (window.tinymce && tinymce.get('desc_editor')) tinymce.get('desc_editor').remove();
          tinymce.init({
            selector:'#desc_editor', height:300, base_url:base, suffix:'.min', license_key:'gpl', menubar:false,
            plugins:'autolink link lists image table code',
            toolbar:'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | link image table | removeformat | code',
            toolbar_mode:'sliding', branding:false,
            setup:(ed)=>{ ed.on('init',()=>{ ed.setContent(initial) }); ed.on('change keyup undo redo input',()=>{ const html=ed.getContent(); if($wire.get('lang')==='id'){ $wire.set('description_id',html);} else { $wire.set('description_en',html);} }); },
            file_picker_types:'image', file_picker_callback:(cb)=>{ const routePrefix='/laravel-filemanager?type=image'; const open=(url,w=980,h=600)=>{const W=innerWidth,H=innerHeight,L=(W-w)/2,T=(H-h)/2; const f=['toolbar=no','location=no','status=no','menubar=no','scrollbars=yes','resizable=yes',`width=${w}`,`height=${h}`,`top=${T}`,`left=${L}`].join(','); const win=window.open(url,'LFM_Popup',f); if(win&&win.focus)win.focus(); return win;}; const old=window.SetUrl; let pop=null,done=false,iv=null; const restore=()=>{ if(done) return; done=true; if(iv) clearInterval(iv); if(old){window.SetUrl=old}else{try{delete window.SetUrl}catch(e){window.SetUrl=undefined}}; window.removeEventListener('message',onMsg,false); }; const onMsg=(ev)=>{const d=ev?.data||{}; if((d.mceAction||'')==='fileSelected'){const f=d.file||(d.files&&d.files[0])||{}; if(f.url){cb(f.url,{alt:f.name||''}); try{pop&&pop.close&&pop.close()}catch(e){} restore();}}}; window.addEventListener('message',onMsg,false); window.SetUrl=(items)=>{try{const a=Array.isArray(items)?items:(items?[items]:[]); const f=a[0]||{}; if(f.url) cb(f.url,{alt:f.name||''});}finally{try{pop&&pop.close&&pop.close()}catch(e){} restore();}}; pop=open(routePrefix); iv=setInterval(()=>{ if(!pop || pop.closed) restore(); },700); setTimeout(()=>restore(),180000); }
          });" x-effect=" const ed = window.tinymce ? tinymce.get('desc_editor') : null; if (ed) { const html = $wire.lang==='id' ? ($wire.get('description_id')||'') : ($wire.get('description_en')||''); if (html !== ed.getContent()) ed.setContent(html); } ">
                        <textarea id="desc_editor"></textarea>
                    </div>
                    @error('description_en') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    @error('description_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div x-show="$wire.get('source')!=='gallery'" class="mb-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Content ({{ strtoupper($lang) }})</label>
                    <div wire:ignore wire:key="content-editor" x-data data-base="{{ asset('tinymce') }}" data-initial="{{ $lang==='id' ? ($content_id ?? '') : ($content_en ?? '') }}" x-init="
          const base=$el.dataset.base; const initial=$el.dataset.initial||'';
          if (window.tinymce && tinymce.get('content_editor')) tinymce.get('content_editor').remove();
          tinymce.init({
            selector:'#content_editor', height:300, base_url:base, suffix:'.min', license_key:'gpl', menubar:'file edit view insert format tools table',
            plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
            toolbar:'undo redo | styles | bold italic underline | bullist numlist | alignleft aligncenter alignright | link image table | code removeformat | fullscreen preview',
            toolbar_mode:'sliding', branding:false,
            setup:(ed)=>{ ed.on('init',()=>{ ed.setContent(initial) }); ed.on('change keyup undo redo',()=>{ const html=ed.getContent(); if($wire.get('lang')==='id'){ $wire.set('content_id',html);} else { $wire.set('content_en',html);} }); },
            file_picker_types:'image', file_picker_callback:(cb)=>{ const routePrefix='/laravel-filemanager?type=image'; const open=(url,w=980,h=600)=>{const W=innerWidth,H=innerHeight,L=(W-w)/2,T=(H-h)/2; const f=['toolbar=no','location=no','status=no','menubar=no','scrollbars=yes','resizable=yes',`width=${w}`,`height=${h}`,`top=${T}`,`left=${L}`].join(','); const win=window.open(url,'LFM_Popup',f); if(win&&win.focus)win.focus(); return win;}; const old=window.SetUrl; let pop=null,done=false,iv=null; const restore=()=>{ if(done) return; done=true; if(iv) clearInterval(iv); if(old){window.SetUrl=old}else{try{delete window.SetUrl}catch(e){window.SetUrl=undefined}}; window.removeEventListener('message',onMsg,false); }; const onMsg=(ev)=>{const d=ev?.data||{}; if((d.mceAction||'')==='fileSelected'){const f=d.file||(d.files&&d.files[0])||{}; if(f.url){cb(f.url,{alt:f.name||''}); try{pop&&pop.close&&pop.close()}catch(e){} restore();}}}; window.addEventListener('message',onMsg,false); window.SetUrl=(items)=>{try{const a=Array.isArray(items)?items:(items?[items]:[]); const f=a[0]||{}; if(f.url) cb(f.url,{alt:f.name||''});}finally{try{pop&&pop.close&&pop.close()}catch(e){} restore();}}; pop=open(routePrefix); iv=setInterval(()=>{ if(!pop || pop.closed) restore(); },700); setTimeout(()=>restore(),180000); }
          });" x-effect=" const ed = window.tinymce ? tinymce.get('content_editor') : null; if (ed) { const html = $wire.lang==='id' ? ($wire.get('content_id')||'') : ($wire.get('content_en')||''); if (html !== ed.getContent()) ed.setContent(html); } ">
                        <textarea id="content_editor"></textarea>
                    </div>
                    @error('content_en') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    @error('content_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="grid sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Publikasi</label>
                        <input type="date" wire:model.defer="tanggal_publikasi" class="w-full border   p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Publikasi</label>
                        <select wire:model.defer="publikasi" class="w-full border   p-2">
                            <option value="draf">Draf</option>
                            <option value="publish">Publish</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select wire:model.defer="status" class="w-full border   p-2">
                            <option value="on">On</option>
                            <option value="off">Off</option>
                        </select>
                    </div>
                </div>

                <div x-show="$wire.get('source')!=='gallery'">
                    <label class="block text-sm font-medium mb-1">Gambar</label>
                    <input type="file" accept="image/*" wire:model="image" class="w-full border   p-2">
                    <div class="mt-2 text-sm text-slate-500" wire:loading wire:target="image">Mengunggah…</div>

                    @if($image)
                    <div class="mt-3"><img src="{{ $image->temporaryUrl() }}" class="max-h-52   border"></div>
                    @elseif($imagePreview)
                    <div class="mt-3"><img src="{{ $imagePreview }}" class="max-h-52   border"></div>
                    @endif
                    @error('image') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>


                <div x-show="$wire.get('source')==='gallery'" x-cloak wire:key="gallery-fields" class="space-y-4">

                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Type</label>
                            <select wire:model.defer="gallery_type" class="w-full border   p-2">
                                <option value="photo">Photo</option>
                                <option value="video">Video</option>
                            </select>
                            @error('gallery_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Database</label>
                            <select wire:model.defer="database_id" class="w-full border   p-2">
                                <option value="">Pilih database…</option>
                                @foreach($databaseOptions as $opt)
                                <option value="{{ $opt['id'] }}">{{ $opt['label'] }}</option>
                                @endforeach
                            </select>
                            @error('database_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Link file sesuai bahasa --}}
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Link File (ID)</label>
                            <input type="url" wire:model.defer="file_id"
                                class="w-full border   p-2"
                                placeholder="https://drive.google.com/..., https://example.com/file-id">
                            @error('file_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Link File (EN)</label>
                            <input type="url" wire:model.defer="file_en"
                                class="w-full border   p-2"
                                placeholder="https://drive.google.com/..., https://example.com/file-en">
                            @error('file_en') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Preview ringan kalau type = photo dan URL langsung gambar --}}
                    @if($gallery_type==='photo')
                    <div class="grid sm:grid-cols-2 gap-3">
                        @if($file_id)
                        <div>
                            <div class="text-xs text-slate-500 mb-1">Preview (ID)</div>
                            <img src="{{ $file_id }}" onerror="this.style.display='none'" class="max-h-52   border w-full object-cover">
                        </div>
                        @endif
                        @if($file_en)
                        <div>
                            <div class="text-xs text-slate-500 mb-1">Preview (EN)</div>
                            <img src="{{ $file_en }}" onerror="this.style.display='none'" class="max-h-52   border w-full object-cover">
                        </div>
                        @endif
                    </div>
                    @else
                    {{-- untuk video: tampilkan link saja (embed opsional) --}}
                    <div class="space-y-1 text-sm">
                        @if($file_id)
                        <a href="{{ $file_id }}" target="_blank" class="text-blue-600 hover:underline">Buka video (ID)</a>
                        @endif
                        @if($file_en)
                        <a href="{{ $file_en }}" target="_blank" class="text-blue-600 hover:underline">Buka video (EN)</a>
                        @endif
                    </div>
                    @endif
                </div>


                <div class="pt-2">
                    <button type="button" wire:click="save" class="px-4 py-2 bg-blue-600 text-white   hover:bg-blue-700">Simpan</button>
                </div>

                @if (session()->has('success'))
                <div class="p-3 bg-green-100 text-green-700   mt-3">{{ session('success') }}</div>
                @endif
            </div>
        </div>
    </div>


</div>
