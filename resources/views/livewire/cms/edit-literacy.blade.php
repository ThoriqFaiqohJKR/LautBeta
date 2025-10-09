<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    {{-- resources/views/livewire/cms/edit-literacy.blade.php --}}
    <div>
        {{-- resources/views/livewire/cms/edit-literacy.blade.php --}}
        <div class="max-w-4xl mx-auto p-6 sm:p-10">
            <div class="bg-white rounded-2xl border p-6 sm:p-10">
                <h2 class="text-2xl font-semibold mb-6">Edit Literacy ({{ ucfirst($type) }})</h2>

                {{-- Switch Language (EN / ID) --}}
                <div class="flex gap-2 mb-6"> 
                    <button type="button"
                        class="px-3 py-1 rounded border {{ $lang==='en' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                        wire:click="$set('lang','en')">EN</button>
                    <button type="button"
                        class="px-3 py-1 rounded border {{ $lang==='id' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                        wire:click="$set('lang','id')">ID</button>
                </div>

                <div class="space-y-6">
                    {{-- Title (switch sesuai bahasa) --}}
                    <div x-data="{ lang: @entangle('lang') }">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Title (<span class="uppercase" x-text="lang.toUpperCase()"></span>)
                        </label>
                        <input x-show="lang==='en'" x-cloak type="text" wire:model.defer="title_en" class="w-full border rounded p-2">
                        <input x-show="lang==='id'" x-cloak type="text" wire:model.defer="title_id" class="w-full border rounded p-2">
                        @error('title_en') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        @error('title_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description ({{ strtoupper($lang) }})</label>
                        <div wire:ignore wire:key="desc-editor" x-data
                            data-base="{{ asset('tinymce') }}"
                            data-initial="{{ $lang==='id' ? ($description_id ?? '') : ($description_en ?? '') }}"
                            x-init="
                   const base=$el.dataset.base;
                   const initial=$el.dataset.initial||'';
                   if (window.tinymce && tinymce.get('desc_editor')) tinymce.get('desc_editor').remove();
                   tinymce.init({
                     selector:'#desc_editor',
                     height:300,
                     base_url:base,
                     suffix:'.min',
                     license_key:'gpl',
                     menubar:false,
                     plugins:'autolink link lists image table code',
                     toolbar:'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | link image table | removeformat | code',
                     toolbar_mode:'sliding',
                     branding:false,
                     setup:(ed)=>{
                       ed.on('init',()=>{ ed.setContent(initial) });
                       ed.on('change keyup undo redo input',()=>{
                         const html=ed.getContent();
                         if($wire.get('lang')==='id'){ $wire.set('description_id', html); }
                         else { $wire.set('description_en', html); }
                       });
                     },
                     file_picker_types:'image',
                     file_picker_callback:(cb)=>{ 
                       const routePrefix='/laravel-filemanager?type=image';
                       const open=(url,w=980,h=600)=>{const W=innerWidth,H=innerHeight,L=(W-w)/2,T=(H-h)/2;const features=['toolbar=no','location=no','status=no','menubar=no','scrollbars=yes','resizable=yes',`width=${w}`,`height=${h}`,`top=${T}`,`left=${L}`].join(',');const win=window.open(url,'LFM_Popup',features); if(win&&win.focus)win.focus(); return win;};
                       const old=window.SetUrl; let pop=null,done=false,iv=null;
                       const restore=()=>{ if(done) return; done=true; if(iv) clearInterval(iv); if(old){window.SetUrl=old}else{try{delete window.SetUrl}catch(e){window.SetUrl=undefined}}; window.removeEventListener('message',onMsg,false); };
                       const onMsg=(ev)=>{const d=ev?.data||{}; if((d.mceAction||'')==='fileSelected'){const f=d.file||(d.files&&d.files[0])||{}; if(f.url){cb(f.url,{alt:f.name||''}); try{pop&&pop.close&&pop.close()}catch(e){} restore();}}};
                       window.addEventListener('message',onMsg,false);
                       window.SetUrl=(items)=>{try{const a=Array.isArray(items)?items:(items?[items]:[]); const f=a[0]||{}; if(f.url) cb(f.url,{alt:f.name||''});}finally{try{pop&&pop.close&&pop.close()}catch(e){} restore();}};
                       pop=open(routePrefix); iv=setInterval(()=>{ if(!pop || pop.closed) restore(); },700); setTimeout(()=>restore(),180000);
                     }
                   });
                 "
                            x-effect="
  const ed = window.tinymce ? tinymce.get('desc_editor') : null;
  if (ed) {
    const html = $wire.lang==='id' ? ($wire.get('description_id')||'') : ($wire.get('description_en')||'');
    if (html !== ed.getContent()) ed.setContent(html);
  }
  ">
                            <textarea id="desc_editor"></textarea>
                        </div>
                        @error('description_en') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        @error('description_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Content khusus Case Report --}}
                    @if($type==='case_report')
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Content ({{ strtoupper($lang) }})</label>
                        <div
                            wire:ignore
                            x-data
                            data-base="{{ asset('tinymce') }}"
                            data-initial="{{ $lang==='id' ? (($content_id ?? $content_id) ?? '') : (($content_en ?? $content_en) ?? '') }}"
                            x-init="
      const base = $el.dataset.base;
      const initial = $el.dataset.initial || '';
      if (window.tinymce && tinymce.get('about_desc')) tinymce.get('about_desc').remove();

      tinymce.init({
        selector: '#about_desc',
        height: 300,
        min_height: 200,
        max_height: 400,
        base_url: base,
        suffix: '.min',
        license_key: 'gpl',

        plugins: 'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
        menubar: 'file edit view insert format tools table',
        toolbar: 'undo redo | styles | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code removeformat | fullscreen preview',
        toolbar_mode: 'sliding',

        style_formats: [
  {title:'Paragraph',format:'p'},
  {title:'Headings',items:[
    {title:'H1',format:'h1'},{title:'H2',format:'h2'},{title:'H3',format:'h3'}
  ]},
  {title:'Inline',items:[
    {title:'Bold',inline:'strong'},{title:'Italic',inline:'em'},
    {title:'Underline',inline:'span',styles:{'text-decoration':'underline'}}
  ]}
],
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
            if ($wire.get('lang') === 'id') {
              $wire.set('content_id', html);
              $wire.set('content_id', html);
            } else {
              $wire.set('content_en', html);
              $wire.set('content_en', html);
            }
          });
        },

        file_picker_types: 'image',
        file_picker_callback: (cb, value, meta) => {
          if (meta.filetype !== 'image') return;

          const routePrefix = '/laravel-filemanager?type=image';

          const openPopup = (url, w=980, h=600) => {
            const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
            const dualScreenTop  = window.screenTop  !== undefined ? window.screenTop  : window.screenY;
            const width  = window.innerWidth  || document.documentElement.clientWidth  || screen.width;
            const height = window.innerHeight || document.documentElement.clientHeight || screen.height;
            const systemZoom = width / window.screen.availWidth;
            const left = (width  - w) / 2 / systemZoom + dualScreenLeft;
            const top  = (height - h) / 2 / systemZoom + dualScreenTop;
            const features = [
              'toolbar=no','location=no','status=no','menubar=no',
              'scrollbars=yes','resizable=yes',
              `width=${w}`,`height=${h}`,
              `top=${top}`,`left=${left}`
            ].join(',');
            const win = window.open(url, 'LFM_Popup', features);
            if (win && win.focus) win.focus();
            return win;
          };

          const oldSetUrl = window.SetUrl;
          let restored = false;
          let popupRef = null;
          let closedPoll = null;

          const restore = () => {
            if (restored) return;
            restored = true;
            if (closedPoll) clearInterval(closedPoll);
            if (oldSetUrl) {
              window.SetUrl = oldSetUrl;
            } else {
              try { delete window.SetUrl } catch(e) { window.SetUrl = undefined }
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
                try { popupRef && popupRef.close && popupRef.close(); } catch(e){}
                restore();
              }
            }
          };
          window.addEventListener('message', onMessage, false);

          window.SetUrl = (items) => {
            try {
              const a = Array.isArray(items) ? items : (items ? [items] : []);
              const f = a[0] || {};
              if (f.url) cb(f.url, { alt: f.name || '' });
            } finally {
              try { popupRef && popupRef.close && popupRef.close(); } catch(e){}
              restore();
            }
          };

          popupRef = openPopup(routePrefix, 980, 600);
          closedPoll = setInterval(() => {
            if (!popupRef || popupRef.closed) {
              restore();
            }
          }, 700);
          setTimeout(() => restore(), 180000);
        },

        init_instance_callback: (editor) => {
          const helpText = editor.getContainer().querySelector('.tox-statusbar__text-container');
          if (helpText) helpText.style.display = 'none';
          const resizeHandle = editor.getContainer().querySelector('.tox-statusbar__resize-handle');
          if (resizeHandle) {
            resizeHandle.style.marginLeft = 'auto';
            resizeHandle.style.marginRight = '4px';
            resizeHandle.style.cursor = 'se-resize';
          }
        }
      });

      window.addEventListener('setEditor', (e) => {
        const ed = tinymce.get('about_desc');
        if (ed) ed.setContent(e.detail?.content || '');
      });
     "
                            x-effect="
      const ed = window.tinymce ? tinymce.get('about_desc') : null;
      if (ed) {
        const html = $wire.lang==='id' ? ($wire.get('content_id')||$wire.get('content_id')||'') : ($wire.get('content_en')||$wire.get('content_en')||'');
        if (html !== ed.getContent()) ed.setContent(html);
      }
    ">
                            <textarea id="about_desc"></textarea>
                        </div>
                        @error('content_en') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                        @error('content_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    {{-- Meta --}}
                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Publikasi</label>
                            <input type="date" wire:model.defer="tanggal_publikasi" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Publikasi</label>
                            <select wire:model.defer="publikasi" class="w-full border rounded p-2">
                                <option value="draf">Draf</option>
                                <option value="publish">Publish</option>
                            </select>
                        </div>
                        @if($type==='case_report')
                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select wire:model.defer="status" class="w-full border rounded p-2">
                                <option value="on">On</option>
                                <option value="off">Off</option>
                            </select>
                        </div>
                        @endif
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Gambar</label>
                        <input type="file" accept="image/*" wire:model="image" class="w-full border rounded p-2">
                        @if($imagePreview)
                        <div class="mt-3"><img src="{{ $imagePreview }}" class="max-h-52 rounded border"></div>
                        @endif
                    </div>

                    {{-- File Upload khusus Journal --}}
                    @if($type==='journal')
                    <div>
                        <label class="block text-sm font-medium mb-1">File (PDF/DOC/PPT)</label>
                        <input type="file" wire:model="file" class="w-full border rounded p-2">
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="pt-2">
                        <button type="button" wire:click="update" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                    </div>

                    @if (session()->has('success'))
                    <div class="p-3 bg-green-100 text-green-700 rounded mt-3">{{ session('success') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>