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

                <div x-show="$wire.get('source')!=='gallery'" class="mb-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Content ({{ strtoupper($lang) }})</label>
                    <div
                        wire:ignore
                        wire:key="content-{{ $lang }}"
                        x-data
                        data-base="{{ asset('tinymce') }}"
                        data-initial="{{ $lang==='id' ? ($content_id ?? '') : ($content_en ?? '') }}"
                        x-init="
        const base=$el.dataset.base, initial=$el.dataset.initial||'';
        if (window.tinymce && tinymce.get('content_editor')) tinymce.get('content_editor').remove();

        tinymce.init({
          selector:'#content_editor',
          height:340,
          min_height:220,
          max_height:520,
          base_url:base,
          suffix:'.min',
          license_key:'gpl',
          plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
          menubar:'file edit view insert format tools table',
          toolbar:'undo redo | styles | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code removeformat | fullscreen preview',
          toolbar_mode:'sliding',
          block_formats:'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
          toolbar_sticky:true,
          promotion:false,
          branding:false,
          statusbar:true,
          elementpath:false,
          resize:true,
          forced_root_block:'p',

          setup:(ed)=>{
            ed.on('init',()=>{ ed.setContent(initial) });
            ed.on('change keyup undo redo',()=>{
              const html=ed.getContent();
              if(@this.get('lang')==='id'){ @this.set('content_id', html); } else { @this.set('content_en', html); }
            });
          },

          file_picker_types:'image',
          file_picker_callback:(cb,value,meta)=>{
            if(meta.filetype!=='image') return;
            const routePrefix='/laravel-filemanager?type=image';

            const openPopup=(url,w=980,h=600)=>{
              const sl = window.screenLeft ?? window.screenX;
              const st = window.screenTop ?? window.screenY;
              const ww = window.innerWidth || document.documentElement.clientWidth || screen.width;
              const wh = window.innerHeight|| document.documentElement.clientHeight|| screen.height;
              const zoom = ww / window.screen.availWidth;
              const left = (ww - w) / 2 / zoom + sl;
              const top  = (wh - h) / 2 / zoom + st;
              const features=[
                'toolbar=no','location=no','status=no','menubar=no',
                'scrollbars=yes','resizable=yes',
                `width=${w}`,`height=${h}`,`top=${top}`,`left=${left}`
              ].join(',');
              const win=window.open(url,'LFM_Popup',features);
              if(win&&win.focus) win.focus();
              return win;
            };

            const oldSetUrl=window.SetUrl;
            let restored=false;
            let popupRef=null;
            let poll=null;

            const restore=()=>{
              if(restored) return; restored=true;
              if(poll) clearInterval(poll);
              if(oldSetUrl){ window.SetUrl=oldSetUrl; }
              else { try{ delete window.SetUrl }catch(e){ window.SetUrl=undefined } }
              window.removeEventListener('message', onMessage, false);
            };

            const onMessage=(ev)=>{
              const data=ev?.data||{};
              const action=data.mceAction || ev?.mceAction;
              if(action==='fileSelected'){
                const f=data.file || (data.files && data.files[0]) || {};
                if(f && f.url){
                  cb(f.url,{alt:f.name||''});
                  try{ popupRef && popupRef.close && popupRef.close(); }catch(e){}
                  restore();
                }
              }
            };
            window.addEventListener('message', onMessage, false);

            window.SetUrl=(items)=>{
              try{
                const arr=Array.isArray(items)?items:(items?[items]:[]);
                const f=arr[0]||{};
                if(f.url) cb(f.url,{alt:f.name||''});
              }finally{
                try{ popupRef && popupRef.close && popupRef.close(); }catch(e){}
                restore();
              }
            };

            popupRef=openPopup(routePrefix,980,600);
            poll=setInterval(()=>{ if(!popupRef || popupRef.closed){ restore(); } }, 700);
            setTimeout(()=>restore(), 180000);
          },

       init_instance_callback:(editor)=>{
  const handle=editor.getContainer().querySelector('.tox-statusbar__resize-handle');
  if(handle){
    handle.style.marginLeft='auto';
    handle.style.marginRight='4px';
    handle.style.cursor='se-resize';
  }
}

        });

        window.addEventListener('set-content', (e)=>{
          const ed=tinymce.get('content_editor'); if(ed) ed.setContent(e.detail?.content||'');
        });
      ">
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
                            <select wire:model.live="gallery_type" class="w-full border p-2">
                                <option value="photo">Photo</option>
                                <option value="video">Video (YouTube)</option>
                            </select>
                            @error('gallery_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Database</label>
                            <select wire:model.defer="database_id" class="w-full border p-2">
                                <option value="">Pilih database…</option>
                                @foreach($databaseOptions as $opt)
                                <option value="{{ $opt['id'] }}">{{ $opt['label'] }}</option>
                                @endforeach
                            </select>
                            @error('database_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- PAKAI wire:key BERBEDA untuk memaksa render ulang --}}
                    @if ($gallery_type === 'video')
                    <div wire:key="gallery-type-video">
                        <label class="block text-sm font-medium mb-1">
                            Link YouTube ({{ strtoupper($lang) }})
                        </label>
                        <input type="url"
                            wire:model.live="{{ $lang === 'id' ? 'file_id' : 'file_en' }}"
                            wire:key="youtube_link"
                            class="w-full border p-2 rounded"
                            placeholder="https://www.youtube.com/watch?v=xxxxxx">
                        @error($lang === 'id' ? 'file_id' : 'file_en')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div class="mt-3 space-y-2" wire:key="gallery-video-preview">
                            @if($lang === 'id' && $yt_id)
                            <iframe class="w-full aspect-video rounded"
                                src="https://www.youtube.com/embed/{{ $yt_id }}"
                                allowfullscreen></iframe>
                            @elseif($lang === 'en' && $yt_en)
                            <iframe class="w-full aspect-video rounded"
                                src="https://www.youtube.com/embed/{{ $yt_en }}"
                                allowfullscreen></iframe>
                            @endif
                        </div>
                    </div>
                    @else
                    <div wire:key="gallery-type-photo">
                        <label class="block text-sm font-medium mb-1">
                            Upload Foto ({{ strtoupper($lang) }})
                        </label>

                        <input type="file"
                            wire:model="{{ $lang === 'id' ? 'file_id' : 'file_en' }}"
                            wire:key="photo_upload"
                            accept="image/*"
                            class="w-full border p-2 rounded">

                        @error($lang === 'id' ? 'file_id' : 'file_en')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        @if($lang === 'id' && is_object($file_id))
                        <img src="{{ $file_id->temporaryUrl() }}" class="mt-2 max-h-52 w-full object-cover border rounded">
                        @elseif($lang === 'en' && is_object($file_en))
                        <img src="{{ $file_en->temporaryUrl() }}" class="mt-2 max-h-52 w-full object-cover border rounded">
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
