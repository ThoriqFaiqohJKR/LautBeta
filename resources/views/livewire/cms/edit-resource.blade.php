<div class="max-w-4xl mx-auto p-6 sm:p-10">
    <div class="gap-2 flex mb-4">
        <a href="{{ route('cms.page.index.resource', ['locale' => app()->getLocale()]) }}">
            <p class="text-xl hover:underline">Page Resource</p>
        </a>
        <p> > </p>
        <p class="text-xl text-blue-700">Edit {{ ucfirst($section) }}</p>
    </div>

    <form wire:submit.prevent="save" class="bg-white border p-6 sm:p-10 space-y-6">
        <div class="text-xl font-semibold">Edit {{ ucfirst($section) }}</div>

        <div class="flex gap-2">
            <button type="button"
                class="px-3 py-1 border {{ $lang==='en' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                wire:click="$set('lang','en')">EN</button>
            <button type="button"
                class="px-3 py-1 border {{ $lang==='id' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                wire:click="$set('lang','id')">ID</button>
        </div>

        <div x-data="{ lang:@entangle('lang') }">
            <label class="block text-sm font-medium mb-1">
                Title (<span class="uppercase" x-text="lang.toUpperCase()"></span>)
            </label>
            <input x-show="lang==='en'" x-cloak type="text" wire:model.defer="title_en" class="w-full border p-2">
            <input x-show="lang==='id'" x-cloak type="text" wire:model.defer="title_id" class="w-full border p-2">
            @error('title_en') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            @error('title_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div x-data="{ lang:@entangle('lang') }">
            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-2">Description ({{ strtoupper($lang) }})</label>

                <div
                    wire:ignore
                    wire:key="desc-{{ $lang }}"
                    x-data
                    data-base="{{ asset('tinymce') }}"
                    data-id="desc_editor_{{ $lang }}"
                    data-initial="{{ $lang==='id' ? ($description_id ?? '') : ($description_en ?? '') }}"
                    x-init="
              const base   = $el.dataset.base;
              const edId   = $el.dataset.id;
              const initHT = $el.dataset.initial || '';

              if (window.tinymce && tinymce.get(edId)) tinymce.get(edId).remove();

              tinymce.init({
                selector:'#'+edId,
                height:300,
                base_url:base,
                suffix:'.min',
                license_key:'gpl',
                plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
                menubar:'file edit view insert format tools table',
                toolbar:'undo redo | styles | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code removeformat | fullscreen preview',
                block_formats:'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
                promotion:false,
                branding:false,
                statusbar:true,
                forced_root_block:'p',

                /* URL absolut utk gambar */
                relative_urls:false,
                remove_script_host:false,
                convert_urls:true,
                document_base_url:'{{ url('/') }}/',

                setup:(ed)=>{
                  ed.on('init',()=>{ ed.setContent(initHT) })
                  ed.on('blur',()=>{
                    const html=ed.getContent()
                    if(@this.get('lang')==='id'){ @this.set('description_id', html) }
                    else{ @this.set('description_en', html) }
                  })
                },

                file_picker_types:'image',
                file_picker_callback:(cb)=>{
                  const routePrefix='/laravel-filemanager?type=image';

                  const open=(url,w=980,h=600)=>{
                    const sl=window.screenLeft??window.screenX, st=window.screenTop??window.screenY;
                    const ww=window.innerWidth||document.documentElement.clientWidth||screen.width;
                    const wh=window.innerHeight||document.documentElement.clientHeight||screen.height;
                    const zoom=ww/window.screen.availWidth;
                    const left=(ww-w)/2/zoom+sl, top=(wh-h)/2/zoom+st;
                    const win=window.open(url,'LFM_Popup',[
                      'toolbar=no','location=no','status=no','menubar=no','scrollbars=yes','resizable=yes',
                      `width=${w}`,`height=${h}`,`top=${top}`,`left=${left}`
                    ].join(','));
                    if(win&&win.focus) win.focus(); return win;
                  };

                  const old=window.SetUrl; let done=false; let pop=null; let poll=null;
                  const cleanup=()=>{ if(done) return; done=true; if(poll) clearInterval(poll);
                    if(old){ window.SetUrl=old } else { try{ delete window.SetUrl }catch(e){ window.SetUrl=undefined } }
                    window.removeEventListener('message', onMsg, false);
                  };
                  const toAbs=(u)=>/^https?:\/\//i.test(u)?u:(u.startsWith('/')?location.origin+u:location.origin+'/'+u);
                  const onMsg=(ev)=>{
                    const d=ev?.data||{}; const act=d.mceAction||ev?.mceAction;
                    if(act==='fileSelected'){
                      const f=d.file || (d.files&&d.files[0]) || {};
                      if(f && (f.url||f.full_url||f.thumb_url||f.path)){
                        cb(toAbs(f.url||f.full_url||f.thumb_url||f.path), {alt:f.name||''});
                        try{pop&&pop.close&&pop.close()}catch(e){} cleanup();
                      }
                    }
                  };
                  window.addEventListener('message', onMsg, false);
                  window.SetUrl=(items)=>{
                    try{
                      const a=Array.isArray(items)?items:(items?[items]:[]);
                      const f=a[0]||{};
                      if(f && (f.url||f.full_url||f.thumb_url||f.path)){
                        cb(toAbs(f.url||f.full_url||f.thumb_url||f.path), {alt:f.name||''});
                      }
                    } finally { try{pop&&pop.close&&pop.close()}catch(e){} cleanup(); }
                  };

                  pop=open(routePrefix,980,600);
                  poll=setInterval(()=>{ if(!pop||pop.closed){ cleanup() } },700);
                  setTimeout(()=>cleanup(),180000);
                },

                init_instance_callback:(editor)=>{
                  const h=editor.getContainer().querySelector('.tox-statusbar__resize-handle');
                  if(h){ h.style.marginLeft='auto'; h.style.marginRight='4px'; h.style.cursor='se-resize' }
                }
              });
            ">
                    <textarea id="desc_editor_{{ $lang }}"></textarea>
                </div>

                @error('description_en') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                @error('description_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        @if($section === 'report' || $section === 'database')
        <div x-data="{ lang:@entangle('lang') }">
            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Content ({{ strtoupper($lang) }})
                </label>

                <div
                    wire:ignore
                    wire:key="desc-{{ $lang }}"
                    x-data
                    data-base="{{ asset('tinymce') }}"
                    data-id="desc_editor_{{ $lang }}"
                    data-initial="{{ $lang==='id' ? ($content_id ?? '') : ($content_en ?? '') }}"
                    x-init="
      const base   = $el.dataset.base;
      const edId   = $el.dataset.id;
      const initHT = $el.dataset.initial || '';

      if (window.tinymce && tinymce.get(edId)) tinymce.get(edId).remove();

      tinymce.init({
        selector:'#'+edId,
        height:300,
        base_url:base,
        suffix:'.min',
        license_key:'gpl',
        plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
        menubar:'file edit view insert format tools table',
        toolbar:'undo redo | styles | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code removeformat | fullscreen preview',
        block_formats:'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
        promotion:false,
        branding:false,
        statusbar:true,
        forced_root_block:'p',

        relative_urls:false,
        remove_script_host:false,
        convert_urls:true,
        document_base_url:'{{ url('/') }}/',

        setup:(ed)=>{
          ed.on('init',()=>{ ed.setContent(initHT) })
          ed.on('blur',()=>{
            const html=ed.getContent()
            if(@this.get('lang')==='id'){ @this.set('content_id', html) }
            else{ @this.set('content_en', html) }
          })
        },

        file_picker_types:'image',
        file_picker_callback:(cb)=>{
          const routePrefix='/laravel-filemanager?type=image';

          const open=(url,w=980,h=600)=>{
            const sl=window.screenLeft??window.screenX;
            const st=window.screenTop??window.screenY;
            const ww=window.innerWidth||document.documentElement.clientWidth||screen.width;
            const wh=window.innerHeight||document.documentElement.clientHeight||screen.height;
            const zoom=ww/window.screen.availWidth;
            const left=(ww-w)/2/zoom+sl, top=(wh-h)/2/zoom+st;
            const win=window.open(url,'LFM_Popup',[
              'toolbar=no','location=no','status=no','menubar=no','scrollbars=yes','resizable=yes',
              `width=${w}`,`height=${h}`,`top=${top}`,`left=${left}`
            ].join(','));
            if(win&&win.focus) win.focus();
            return win;
          };

          const old=window.SetUrl; let done=false; let pop=null; let poll=null;
          const cleanup=()=>{ if(done) return; done=true; if(poll) clearInterval(poll);
            if(old){ window.SetUrl=old } else { try{ delete window.SetUrl }catch(e){ window.SetUrl=undefined } }
            window.removeEventListener('message', onMsg, false);
          };

          const toAbs=(u)=>{
            if(/^https?:\/\//i.test(u)) return u;
            if(u.startsWith('/')) return location.origin+u;
            return location.origin+'/'+u;
          };

          const onMsg=(ev)=>{
            const d=ev?.data||{}; const act=d.mceAction||ev?.mceAction;
            if(act==='fileSelected'){
              const f=d.file || (d.files&&d.files[0]) || {};
              if(f && (f.url||f.full_url||f.thumb_url||f.path)){
                const url = toAbs(f.url||f.full_url||f.thumb_url||f.path);
                cb(url,{alt:f.name||''}); try{pop&&pop.close&&pop.close()}catch(e){} cleanup();
              }
            }
          };
          window.addEventListener('message', onMsg, false);

          window.SetUrl=(items)=>{
            try{
              const a=Array.isArray(items)?items:(items?[items]:[]);
              const f=a[0]||{};
              if(f && (f.url||f.full_url||f.thumb_url||f.path)){
                const url = toAbs(f.url||f.full_url||f.thumb_url||f.path);
                cb(url,{alt:f.name||''});
              }
            } finally { try{pop&&pop.close&&pop.close()}catch(e){} cleanup(); }
          };

          pop=open(routePrefix,980,600);
          poll=setInterval(()=>{ if(!pop||pop.closed){ cleanup() } },700);
          setTimeout(()=>cleanup(),180000);
        },

        init_instance_callback:(editor)=>{
          const h=editor.getContainer().querySelector('.tox-statusbar__resize-handle');
          if(h){ h.style.marginLeft='auto'; h.style.marginRight='4px'; h.style.cursor='se-resize' }
        }
      });
    ">
                    <textarea id="desc_editor_{{ $lang }}"></textarea>
                </div>

                @error('content_en') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                @error('content_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>
        </div>
        @endif

        @if($section === 'report')
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Publikasi</label>
                <input type="date" wire:model.defer="tanggal_publikasi" class="w-full border p-2">
                @error('tanggal_publikasi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Publikasi</label>
                <select wire:model.defer="publikasi" class="w-full border p-2">
                    <option value="draf">draf</option>
                    <option value="publish">publish</option>
                </select>
                @error('publikasi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select wire:model.defer="status" class="w-full border p-2">
                    <option value="on">on</option>
                    <option value="off">off</option>
                </select>
                @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Gambar</label>
            <input type="file" accept="image/*" wire:model="image" class="w-full border p-2">
            <div wire:loading wire:target="image" class="text-sm text-slate-500 mt-1">Uploading…</div>
            @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

            @if($image && method_exists($image,'temporaryUrl'))
            <img src="{{ $image->temporaryUrl() }}" class="mt-3 max-h-52 border">
            @elseif(!empty($imagePreview))
            <div class="mt-3 inline-block overflow-hidden rounded-md border shadow-sm bg-slate-50 p-2">
                <img src="{{ $imagePreview }}" class="max-h-52 object-cover rounded-md hover:scale-105 transition duration-300">
                <p class="text-center text-xs text-slate-500 mt-1">Gambar Sebelumnya</p>
            </div>
            @endif

        </div>
        @endif

        @if($section === 'database')
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Publikasi</label>
                <input type="date" wire:model.defer="tanggal_publikasi" class="w-full border p-2">
                @error('tanggal_publikasi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Publikasi</label>
                <select wire:model.defer="publikasi" class="w-full border p-2">
                    <option value="draf">draf</option>
                    <option value="publish">publish</option>
                </select>
                @error('publikasi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select wire:model.defer="status" class="w-full border p-2">
                    <option value="on">on</option>
                    <option value="off">off</option>
                </select>
                @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Gambar</label>
            <input type="file" accept="image/*" wire:model="image" class="w-full border p-2">
            <div wire:loading wire:target="image" class="text-sm text-slate-500 mt-1">Uploading…</div>
            @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

            @if($image && method_exists($image,'temporaryUrl'))
            <img src="{{ $image->temporaryUrl() }}" class="mt-3 max-h-52 border">
            @elseif(!empty($imagePreview))
            <img src="{{ $imagePreview }}" class="mt-3 max-h-52 border">
            @endif
        </div>
        @endif

        @if($section === 'gallery')
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium mb-1">Database</label>
                <select wire:model.defer="database_id" class="w-full border p-2 rounded-md bg-white">
                    @foreach($database_options as $opt)
                    <option value="{{ $opt->id }}">
                        {{ $opt->title_id ?? $opt->title_en ?? ('ID ' . $opt->id) }}
                    </option>
                    @endforeach
                </select>
                @error('database_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select wire:model.defer="status" class="w-full border p-2">
                    <option value="on">on</option>
                    <option value="off">off</option>
                </select>
                @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Publikasi</label>
                <input type="date" wire:model.defer="tanggal_publikasi" class="w-full border p-2">
                @error('tanggal_publikasi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Publikasi</label>
                <select wire:model.defer="publikasi" class="w-full border p-2">
                    <option value="draf">draf</option>
                    <option value="publish">publish</option>
                </select>
                @error('publikasi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select wire:model.live="type" class="w-full border p-2">
                        <option value="">Pilih type…</option>
                        <option value="photo">photo</option>
                        <option value="video">video</option>
                    </select>
                    @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror

                </div>
            </div>
        </div>



        <div x-data="{ lang:@entangle('lang').live, type:@entangle('type').live }">

            <template x-if="type === 'video'">
                <div class="space-y-3">
                    <label class="block text-sm font-medium mb-1">
                        Link YouTube (<span class="uppercase" x-text="lang.toUpperCase()"></span>)
                    </label>

                    <input x-show="lang==='en'" x-cloak
                        wire:model.live="file_en"
                        class="w-full border p-2"
                        placeholder="https://youtube.com/... (EN)">

                    <input x-show="lang==='id'" x-cloak
                        wire:model.live="file_id"
                        class="w-full border p-2"
                        placeholder="https://youtube.com/... (ID)">

                    <div class="mt-2">
                        
                        @if($lang === 'en' && $yt_en)
                        <iframe class="w-full aspect-video rounded"
                            src="https://www.youtube.com/embed/{{ $yt_en }}" allowfullscreen></iframe>
                        @elseif($lang === 'id' && $yt_id)
                        <iframe class="w-full aspect-video rounded"
                            src="https://www.youtube.com/embed/{{ $yt_id }}" allowfullscreen></iframe>


                        @elseif($lang === 'en' && !empty($file_en_preview))
                        <iframe class="w-full aspect-video rounded"
                            src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::of($file_en_preview)->after('v=')->before('&') }}"
                            allowfullscreen></iframe>
                        @elseif($lang === 'id' && !empty($file_id_preview))
                        <iframe class="w-full aspect-video rounded"
                            src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::of($file_id_preview)->after('v=')->before('&') }}"
                            allowfullscreen></iframe>
                        @endif
                    </div>

                </div>
            </template>



            <template x-if="type === 'photo'">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Upload Gambar (<span class="uppercase" x-text="lang.toUpperCase()"></span>)
                    </label>

                    <input x-show="lang==='en'" x-cloak type="file" accept="image/*" wire:model="file_en" class="w-full border p-2">
                    <input x-show="lang==='id'" x-cloak type="file" accept="image/*" wire:model="file_id" class="w-full border p-2">

                    <div wire:loading wire:target="file_en, file_id" class="text-sm text-slate-500 mt-1">Uploading…</div>

                    <template x-if="lang==='en'">
                        <div>
                            @if($file_en && method_exists($file_en,'temporaryUrl'))
                            <div class="mt-3 inline-block overflow-hidden rounded-md border shadow-sm bg-slate-50 p-2">
                                <img src="{{ $file_en->temporaryUrl() }}" class="max-h-52 object-cover rounded-md hover:scale-105 transition duration-300">
                                <p class="text-center text-xs text-slate-500 mt-1">Preview Gambar EN Baru</p>
                            </div>
                            @elseif(!empty($file_en_preview))
                            <div class="mt-3 inline-block overflow-hidden rounded-md border shadow-sm bg-slate-50 p-2">
                                <img src="{{ Storage::exists($file_en_preview) ? Storage::url($file_en_preview) : $file_en_preview }}" class="max-h-52 object-cover rounded-md hover:scale-105 transition duration-300">
                                <p class="text-center text-xs text-slate-500 mt-1">Gambar EN Sebelumnya</p>
                            </div>
                            @endif
                        </div>
                    </template>

                    <template x-if="lang==='id'">
                        <div>
                            @if($file_id && method_exists($file_id,'temporaryUrl'))
                            <div class="mt-3 inline-block overflow-hidden rounded-md border shadow-sm bg-slate-50 p-2">
                                <img src="{{ $file_id->temporaryUrl() }}" class="max-h-52 object-cover rounded-md hover:scale-105 transition duration-300">
                                <p class="text-center text-xs text-slate-500 mt-1">Preview Gambar ID Baru</p>
                            </div>
                            @elseif(!empty($file_id_preview))
                            <div class="mt-3 inline-block overflow-hidden rounded-md border shadow-sm bg-slate-50 p-2">
                                <img src="{{ Storage::exists($file_id_preview) ? Storage::url($file_id_preview) : $file_id_preview }}" class="max-h-52 object-cover rounded-md hover:scale-105 transition duration-300">
                                <p class="text-center text-xs text-slate-500 mt-1">Gambar ID Sebelumnya</p>
                            </div>
                            @endif
                        </div>
                    </template>
                </div>
            </template>
        </div>
        @endif

        <div class="pt-4 flex items-center gap-3">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white">Save</button>
            <a href="{{ url()->previous() }}" class="px-4 py-2 border">Cancel</a>
        </div>

        @if (session('success'))
        <div x-data="{show:true}" x-init="setTimeout(()=>show=false,2000)" x-show="show" x-transition
            class="mt-3 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif
        @if (session('success'))
        <div x-data="{show:true}" x-init="setTimeout(()=>show=false,3000)" x-show="show" x-transition
            class="mt-6 p-4 bg-emerald-100 text-emerald-700 rounded-lg text-center font-medium">
            ✅ Data berhasil disimpan!
        </div>
        @endif

    </form>
</div>
