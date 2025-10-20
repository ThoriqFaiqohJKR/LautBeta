<div>
    <div class="max-w-4xl mx-auto p-6 sm:p-10">
        <div class="gap-2 flex mb-4">
            <a href="{{ route('cms.page.index.literacy', ['locale' => app()->getLocale()]) }}">
                <p class="text-xl hover:underline">Page Literacy</p>
            </a>
            <p> > </p>
            <p class="text-xl text-blue-700">Tambah Literacy</p>
        </div>

        <div class="bg-white border p-6 sm:p-10">
            <h2 class="text-2xl font-semibold mb-6">Tambah Literacy (Journal)</h2>

            <div class="flex gap-2 mb-6">
                <button type="button"
                    class="px-3 py-1 border {{ $lang==='en' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                    wire:click="$set('lang','en')">EN</button>
                <button type="button"
                    class="px-3 py-1 border {{ $lang==='id' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                    wire:click="$set('lang','id')">ID</button>
            </div>

            <div class="space-y-6">
                {{-- Title --}}
                <div x-data="{ lang:@entangle('lang') }">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Title (<span class="uppercase" x-text="lang.toUpperCase()"></span>)
                    </label>
                    <input x-show="lang==='en'" x-cloak type="text" wire:model.defer="title_en" class="w-full border p-2">
                    <input x-show="lang==='id'" x-cloak type="text" wire:model.defer="title_id" class="w-full border p-2">
                    @error('title_en') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @error('title_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description (TinyMCE) --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Description ({{ strtoupper($lang) }})
                    </label>

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

                {{-- Meta --}}
                <div class="grid sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Publikasi</label>
                        <input type="date" wire:model.defer="tanggal_publikasi" class="w-full border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Publikasi</label>
                        <select wire:model.defer="publikasi" class="w-full border p-2">
                            <option value="draf">Draf</option>
                            <option value="publish">Publish</option>
                        </select>
                    </div>
                </div>

                {{-- Image --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Gambar</label>
                    <input type="file" accept="image/*" wire:model="image" class="w-full border p-2">
                    <div wire:loading wire:target="image" class="text-sm text-slate-500 mt-1">Uploading…</div>
                    @if($image && method_exists($image,'temporaryUrl'))
                    <div class="mt-3"><img src="{{ $image->temporaryUrl() }}" class="max-h-52 border"></div>
                    @elseif($imagePreview)
                    <div class="mt-3"><img src="{{ $imagePreview }}" class="max-h-52 border"></div>
                    @endif
                </div>

                <div x-data="{ lang:@entangle('lang') }">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Upload File (PDF/DOC/PPT)
                        (<span class="uppercase" x-text="lang.toUpperCase()"></span>)
                    </label>

                    {{-- File EN --}}
                    <div x-show="lang==='en'" x-cloak>
                        <input type="file" wire:model="file_en_upload" accept=".pdf,.doc,.docx,.ppt,.pptx" class="w-full border p-2">
                        @error('file_en_upload') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- File ID --}}
                    <div x-show="lang==='id'" x-cloak>
                        <input type="file" wire:model="file_id_upload" accept=".pdf,.doc,.docx,.ppt,.pptx" class="w-full border p-2">
                        @error('file_id_upload') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div wire:loading wire:target="file_en_upload,file_id_upload" class="text-sm text-slate-500 mt-1">Uploading…</div>
                    <p class="text-xs text-slate-500 mt-1">Minimal salah satu file diupload (ID atau EN).</p>
                </div>

                {{-- Actions + flash --}}
                <div class="pt-2">
                    <button type="button" wire:click="save" class="px-4 py-2 bg-green-600 text-white hover:bg-green-700">Simpan</button>
                </div>

                @if (session('success'))
                <div x-data="{show:true}" x-init="setTimeout(()=>show=false,2000)" x-show="show" x-transition
                    class="mt-3 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
