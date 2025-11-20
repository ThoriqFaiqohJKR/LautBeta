<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}

    <div class="max-w-6xl mx-auto p-6 sm:p-10">
        <div class="gap-2 flex mb-4">
            <a href="{{ route('cms.page.index.agenda', ['locale' => app()->getLocale()]) }}">
                <p class="text-xl hover:underline">Page Agenda </p>
            </a>
            <p> > </p>
            <p class="text-xl text-blue-700">Tambah Agenda</p>
        </div>
        <div class="bg-white  border p-6 sm:p-10">

            <h2 class="text-2xl font-semibold mb-6">Tambah agenda</h2>


            <div class="flex gap-2 mb-4">
                <button type="button"
                    class="px-3 py-1   border {{ $type==='event' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                    wire:click="$set('type','event')">event</button>
                <button type="button"
                    class="px-3 py-1   border {{ $type==='activity' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                    wire:click="$set('type','activity')">activity</button>
            </div>


            <div class="flex gap-2 mb-6">
                <button type="button"
                    class="px-3 py-1   border {{ $lang==='en' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                    wire:click="$set('lang','en')">EN</button>
                <button type="button"
                    class="px-3 py-1   border {{ $lang==='id' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                    wire:click="$set('lang','id')">ID</button>
            </div>


            <div class="space-y-6">


                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Title ({{ strtoupper($lang) }})
                    </label>

                    @if($lang === 'en')
                    <input
                        wire:key="title-input-en"
                        id="title_en"
                        name="title_en"
                        type="text"
                        autocomplete="off"
                        wire:model.defer="title_en"
                        class="w-full border   p-2"
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
                        class="w-full border   p-2"
                        placeholder="Judul Indonesia">
                    @error('title_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @endif


                </div>


                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Description ({{ strtoupper($lang) }})</label>
                    <div
                        wire:ignore
                        wire:key="desc-{{ $lang }}"
                        x-data
                        data-base="{{ asset('tinymce') }}"
                        data-initial="{{ $lang==='id' ? ($description_id ?? '') : ($description_en ?? '') }}"
                        x-init="
            const base=$el.dataset.base, initial=$el.dataset.initial||'';
            if (window.tinymce && tinymce.get('desc_editor')) tinymce.get('desc_editor').remove();

            tinymce.init({
              selector:'#desc_editor',
              height:300,
              min_height:200,
              max_height:480,
              base_url:base,
              suffix:'.min',
              license_key:'gpl',
              plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
              menubar:'file edit view insert format tools table',
              toolbar:'undo redo | styles | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code removeformat | fullscreen preview',
              toolbar_mode:'sliding',
              style_formats:[
                {title:'Text Styles',items:[
                  {title:'Paragraph',format:'p'},
                  {title:'Headings',items:[{title:'H1',format:'h1'},{title:'H2',format:'h2'},{title:'H3',format:'h3'},{title:'H4',format:'h4'},{title:'H5',format:'h5'},{title:'H6',format:'h6'}]},
                  {title:'Inline',items:[{title:'Bold',inline:'b'},{title:'Italic',inline:'i'},{title:'Underline',inline:'u'},{title:'Strikethrough',inline:'strike'}]}
                ]}
              ],
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
                  if(@this.get('lang')==='id'){ @this.set('description_id', html); } else { @this.set('description_en', html); }
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
                  const events=[
                    'toolbar=no','location=no','status=no','menubar=no',
                    'scrollbars=yes','resizable=yes',
                    `width=${w}`,`height=${h}`,`top=${top}`,`left=${left}`
                  ].join(',');
                  const win=window.open(url,'LFM_Popup',events);
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
                const helpText=editor.getContainer().querySelector('.tox-statusbar__text-container');
                if(helpText) helpText.style.display='none';
                const handle=editor.getContainer().querySelector('.tox-statusbar__resize-handle');
                if(handle){ handle.style.marginLeft='auto'; handle.style.marginRight='4px'; handle.style.cursor='se-resize'; }
              }
            });

            window.addEventListener('set-desc', (e)=>{
              const ed=tinymce.get('desc_editor'); if(ed) ed.setContent(e.detail?.content||'');
            });
          ">
                        <textarea id="desc_editor"></textarea>
                    </div>
                    @error('description_en') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    @error('description_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                </div>


                <div class="mb-5">
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

              /* ===== POPUP WINDOW (bukan modal TinyMCE) ===== */
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
                  const events=[
                    'toolbar=no','location=no','status=no','menubar=no',
                    'scrollbars=yes','resizable=yes',
                    `width=${w}`,`height=${h}`,`top=${top}`,`left=${left}`
                  ].join(',');
                  const win=window.open(url,'LFM_Popup',events);
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
                const helpText=editor.getContainer().querySelector('.tox-statusbar__text-container');
                if(helpText) helpText.style.display='none';
                const handle=editor.getContainer().querySelector('.tox-statusbar__resize-handle');
                if(handle){ handle.style.marginLeft='auto'; handle.style.marginRight='4px'; handle.style.cursor='se-resize'; }
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
                        @error('tanggal_publikasi') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Publikasi</label>
                        <select wire:model.defer="publikasi" class="w-full border   p-2">
                            <option value="draf">Draf</option>
                            <option value="publish">Publish</option>
                        </select>
                        @error('publikasi') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>


                <div>
                    <label class="block text-sm font-medium mb-1">Gambar</label>
                    <input type="file" accept="image/*" wire:model="image" class="w-full border   p-2">
                    <div wire:loading wire:target="image" class="text-sm text-slate-500 mt-1">Uploading…</div>
                    @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

                    @if($image && method_exists($image,'temporaryUrl'))
                    <div class="mt-3"><img src="{{ $image->temporaryUrl() }}" alt="Preview" class="max-h-52   border"></div>
                    @elseif($imagePreview)
                    <div class="mt-3"><img src="{{ $imagePreview }}" alt="Preview" class="max-h-52   border"></div>
                    @endif
                </div>


                <div class="pt-2">
                    <button type="button" wire:click="save" class="px-4 py-2 bg-green-600 text-white   hover:bg-green-700">Simpan</button>
                </div>


                @if (session()->has('success'))
                <div class="p-3 bg-green-100 text-green-700  ">{{ session('success') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>