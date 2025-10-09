<style>
  [x-cloak] {
    display: none
  }
</style>
<div x-data="{
  lfmOpen:false,
  lfmSrc:'',
  _onPick:null,
  lfmPrefix: '{{ '/'.trim(config('lfm.route_prefix','laravel-filemanager'), '/') }}',
  openLfm(type, onPick){
    this._onPick = onPick;
    const base = this.lfmPrefix || '/laravel-filemanager';
    this.lfmSrc = ${base}?type=${type==='image' ? 'image' : 'file'};
    this.lfmOpen = true;
    window.SetUrl = (items) => {
      try{
        const arr = Array.isArray(items) ? items : [items];
        const first = arr && arr.length ? arr[0] : items;
        const file = typeof first === 'string' ? { url:first } : (first || {});
        const url  = file.url || file.thumb_url || file.path || '';
        if(this._onPick && url){ this._onPick(url, file); }
      } finally {
        this.lfmOpen = false;
        this.lfmSrc = '';
        window.SetUrl = null;
        this._onPick = null;
      }
    };
  }
}"
  x-init="
    window.addEventListener('tinymce-fill', (e) => {
      const d = e.detail || {};
      const ed = window.tinymce && tinymce.get(d.target);
      if (ed) ed.setContent(d.content ?? '');
    });

    // Fallback untuk LFM yang mengirim via postMessage
    window.addEventListener('message', (e) => {
      try{
        const msg = e && e.data ? e.data : {};
        if(!msg) return;
        const isLfm = msg.mceAction==='fileSelected' || msg.event==='file:chosen' || msg.lfmEvent==='files:chosen' || msg.event==='lfm:select';
        if(!isLfm) return;
        const f = msg.file || (msg.files && msg.files[0]) || msg.item || {};
        const url = (f && (f.url || f.thumb_url || f.path)) || msg.url || '';
        if(this._onPick && url){ this._onPick(url, f); }
      } finally {
        this.lfmOpen = false;
        this.lfmSrc = '';
        window.SetUrl = null;
        this._onPick = null;
      }
    }, false); }
      } finally {
        this.lfmOpen = false;
        this.lfmSrc = '';
        window.SetUrl = null;
        this._onPick = null;
      }
    }, false);
  ">

  <div class="max-w-4xl mx-auto p-12">
    <div class="bg-white p-12">
      <h2 class="text-2xl font-semibold mb-4">Agenda</h2>

      
      <div class="flex gap-2 mb-4">
        <button type="button"
          class="px-3 py-1 rounded border {{ $section==='event' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}"
          wire:click="setSection('event')">Event</button>
        <button type="button"
          class="px-3 py-1 rounded border {{ $section==='activity' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-300' }}"
          wire:click="setSection('activity')">Activity</button>
      </div>

      
      <div class="flex gap-2 mb-6">
        <button type="button"
          class="px-3 py-1 rounded border {{ $lang==='en' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-300' }}"
          wire:click="setLang('en')">EN</button>
        <button type="button"
          class="px-3 py-1 rounded border {{ $lang==='id' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-300' }}"
          wire:click="setLang('id')">ID</button>
      </div>

      
      @if($section==='event')
      <div class="space-y-5">
        <!-- Title -->
        <div>
          <label class="block text-sm font-medium">Judul Event ({{ strtoupper($lang) }})</label>
          @if($lang==='en')
          <input type="text" wire:model.defer="event.titleEN" class="w-full border rounded p-2">
          @error('event.titleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          @else
          <input type="text" wire:model.defer="event.titleID" class="w-full border rounded p-2">
          @error('event.titleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          @endif
        </div>

        
        <div>
          <label class="block text-sm font-medium">Deskripsi Event ({{ strtoupper($lang) }})</label>

          <div wire:ignore
            wire:key="event-desc-{{ $lang }}"
            x-data
            data-base="{{ asset('tinymce') }}"
            data-initial="{{ $lang==='id' ? ($event['descriptionID'] ?? '') : ($event['descriptionEN'] ?? '') }}"
            x-init="
                 const base=$el.dataset.base, initial=$el.dataset.initial||'';
                 if(window.tinymce && tinymce.get('event_desc')) tinymce.get('event_desc').remove();
                 tinymce.init({
                   selector:'#event_desc',
                   height:300,
                   min_height:200,
                   max_height:400,
                   base_url:base,
                   suffix:'.min',
                   license_key:'gpl',
                   plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
                   menubar:'file edit view insert format tools table',
                   toolbar:'undo redo | styles | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code removeformat | fullscreen preview',
                   toolbar_mode:'sliding',
                   style_formats:[{title:'Text Styles',items:[{title:'Paragraph',format:'p'},{title:'Headings',items:[{title:'H1',format:'h1'},{title:'H2',format:'h2'},{title:'H3',format:'h3'},{title:'H4',format:'h4'},{title:'H5',format:'h5'},{title:'H6',format:'h6'}]},{title:'Inline',items:[{title:'Bold',inline:'b'},{title:'Italic',inline:'i'},{title:'Underline',inline:'u'},{title:'Strikethrough',inline:'strike'}]},{title:'Fonts',items:[{title:'Sans-serif',inline:'span',styles:{'font-family':'Arial,Helvetica,sans-serif'}},{title:'Serif',inline:'span',styles:{'font-family':'Times New Roman,serif'}},{title:'Monospace',inline:'span',styles:{'font-family':'Courier New,monospace'}}]}]}],
                   block_formats:'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
                   toolbar_sticky:true,
                   promotion:false,
                   branding:false,
                   statusbar:true,
                   elementpath:false,
                   resize:true,
                   forced_root_block:'p',
                   setup:(ed)=>{
                     ed.on('init',()=>{ed.setContent(initial)});
                     ed.on('change keyup undo redo',()=>{
                       const html=ed.getContent();
                       if(@this.get('lang')==='id'){ @this.set('event.descriptionID',html); } else { @this.set('event.descriptionEN',html); }
                     });
                   },
                   file_picker_types:'image',
                   file_picker_callback:(cb,value,meta)=>{
                     if(meta.filetype!=='image') return;
                     const url='/laravel-filemanager?type=image';
                     tinymce.activeEditor.windowManager.openUrl({
                       title:'File Manager',url,width:980,height:600,
                       onMessage:(api,msg)=>{const d=msg?.data||{}; if(msg.mceAction==='fileSelected'||d.mceAction==='fileSelected'){const f=d.file||(d.files&&d.files[0])||{}; if(f.url){cb(f.url,{alt:f.name||''}); api.close();}}}
                     });
                   },
                   init_instance_callback:(editor)=>{
                     const helpText=editor.getContainer().querySelector('.tox-statusbar__text-container');
                     if(helpText) helpText.style.display='none';
                     const resizeHandle=editor.getContainer().querySelector('.tox-statusbar__resize-handle');
                     if(resizeHandle){ resizeHandle.style.marginLeft='auto'; resizeHandle.style.marginRight='4px'; resizeHandle.style.cursor='se-resize'; }
                   }
                 });
               ">
            <textarea id="event_desc"></textarea>
          </div>
        </div>

        
        <div class="space-y-2">
          <label class="block text-sm font-medium">Image Event</label>
          <div class="flex gap-2">
            <input type="text" class="w-full border rounded p-2" placeholder="URL gambar dari File Manager…" x-model="$wire.entangle('event.image_path').live">
            <button type="button" class="px-3 py-2 border rounded bg-slate-800 text-white hover:bg-slate-900" @click="openLfm('image', (url)=>{ $wire.set('event.image_path', url) })">Browse</button>
          </div>
          @error('event.image_path') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          @if(!empty($event['image_path']))
          <img src="{{ $event['image_path'] }}" alt="Preview" class="mt-2 max-h-48 rounded border">
          @elseif(!empty($eventImagePath))
          <img src="{{ $eventImagePath }}" alt="Current" class="mt-2 max-h-48 rounded border">
          @endif
        </div>

        
        <div class="mt-2">
          @if($editMode && ($editingSection??null)==='event')
          <button type="button" wire:click="updateEvent" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
          <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Batal</button>
          @else
          <button type="button" wire:click="saveEvent" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
          @endif
        </div>

        @if (session()->has('successEvent'))
        <div class="p-3 bg-green-100 text-green-700 rounded mt-3">{{ session('successEvent') }}</div>
        @endif

        
        <div class="mt-6">
          <label class="block text-sm font-medium mb-1">Daftar Event</label>
          <div class="grid grid-cols-1 gap-4">
            @foreach($eventsList as $item)
            <div class="flex items-start gap-4 border rounded p-3 cursor-pointer hover:bg-slate-50" wire:click="editEvent({{ $item->id }})">
              <img src="{{ $item->image ? (Str::startsWith($item->image, ['http://','https://']) ? $item->image : Storage::url($item->image)) : asset('images/placeholder-80.svg') }}"
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

      <!-- ================= ACTIVITY ================= -->
      @if($section==='activity')
      <div class="space-y-5">
        <!-- Title -->
        <div>
          <label class="block text-sm font-medium">Judul Activity ({{ strtoupper($lang) }})</label>
          @if($lang==='en')
          <input type="text" wire:model.defer="activity.titleEN" class="w-full border rounded p-2">
          @error('activity.titleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          @else
          <input type="text" wire:model.defer="activity.titleID" class="w-full border rounded p-2">
          @error('activity.titleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          @endif
        </div>

        <!-- Description (TinyMCE + LFM unified config) -->
        <div>
          <label class="block text-sm font-medium">Deskripsi Activity ({{ strtoupper($lang) }})</label>

          <div wire:ignore
            wire:key="activity-desc-{{ $lang }}"
            x-data
            data-base="{{ asset('tinymce') }}"
            data-initial="{{ $lang==='id' ? ($activity['descriptionID'] ?? '') : ($activity['descriptionEN'] ?? '') }}"
            x-init="
                 const base=$el.dataset.base, initial=$el.dataset.initial||'';
                 if(window.tinymce && tinymce.get('activity_desc')) tinymce.get('activity_desc').remove();
                 tinymce.init({
                   selector:'#activity_desc',
                   height:300,
                   min_height:200,
                   max_height:400,
                   base_url:base,
                   suffix:'.min',
                   license_key:'gpl',
                   plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
                   menubar:'file edit view insert format tools table',
                   toolbar:'undo redo | styles | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code removeformat | fullscreen preview',
                   toolbar_mode:'sliding',
                   style_formats:[{title:'Text Styles',items:[{title:'Paragraph',format:'p'},{title:'Headings',items:[{title:'H1',format:'h1'},{title:'H2',format:'h2'},{title:'H3',format:'h3'},{title:'H4',format:'h4'},{title:'H5',format:'h5'},{title:'H6',format:'h6'}]},{title:'Inline',items:[{title:'Bold',inline:'b'},{title:'Italic',inline:'i'},{title:'Underline',inline:'u'},{title:'Strikethrough',inline:'strike'}]},{title:'Fonts',items:[{title:'Sans-serif',inline:'span',styles:{'font-family':'Arial,Helvetica,sans-serif'}},{title:'Serif',inline:'span',styles:{'font-family':'Times New Roman,serif'}},{title:'Monospace',inline:'span',styles:{'font-family':'Courier New,monospace'}}]}]}],
                   block_formats:'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
                   toolbar_sticky:true,
                   promotion:false,
                   branding:false,
                   statusbar:true,
                   elementpath:false,
                   resize:true,
                   forced_root_block:'p',
                   setup:(ed)=>{
                     ed.on('init',()=>{ed.setContent(initial)});
                     ed.on('change keyup undo redo',()=>{
                       const html=ed.getContent();
                       if(@this.get('lang')==='id'){ @this.set('activity.descriptionID',html); } else { @this.set('activity.descriptionEN',html); }
                     });
                   },
                   file_picker_types:'image',
                   file_picker_callback:(cb,value,meta)=>{
                     if(meta.filetype!=='image') return;
                     const url='/laravel-filemanager?type=image';
                     tinymce.activeEditor.windowManager.openUrl({
                       title:'File Manager',url,width:980,height:600,
                       onMessage:(api,msg)=>{const d=msg?.data||{}; if(msg.mceAction==='fileSelected'||d.mceAction==='fileSelected'){const f=d.file||(d.files&&d.files[0])||{}; if(f.url){cb(f.url,{alt:f.name||''}); api.close();}}}
                     });
                   },
                   init_instance_callback:(editor)=>{
                     const helpText=editor.getContainer().querySelector('.tox-statusbar__text-container');
                     if(helpText) helpText.style.display='none';
                     const resizeHandle=editor.getContainer().querySelector('.tox-statusbar__resize-handle');
                     if(resizeHandle){ resizeHandle.style.marginLeft='auto'; resizeHandle.style.marginRight='4px'; resizeHandle.style.cursor='se-resize'; }
                   }
                 });
               ">
            <textarea id="activity_desc"></textarea>
          </div>
        </div>

        <!-- Image via LFM (modal) -->
        <div class="space-y-2">
          <label class="block text-sm font-medium">Image Activity</label>
          <div class="flex gap-2">
            <input type="text" class="w-full border rounded p-2" placeholder="URL gambar dari File Manager…" x-model="$wire.entangle('activity.image_path').live">
            <button type="button" class="px-3 py-2 border rounded bg-slate-800 text-white hover:bg-slate-900" @click="openLfm('image', (url)=>{ $wire.set('activity.image_path', url) })">Browse</button>
          </div>
          @error('activity.image_path') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          @if(!empty($activity['image_path']))
          <img src="{{ $activity['image_path'] }}" alt="Preview" class="mt-2 max-h-48 rounded border">
          @elseif(!empty($activityImagePath))
          <img src="{{ $activityImagePath }}" alt="Current" class="mt-2 max-h-48 rounded border">
          @endif
        </div>

        <!-- Actions -->
        <div class="mt-2">
          @if($editMode && ($editingSection??null)==='activity')
          <button type="button" wire:click="updateActivity" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
          <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Batal</button>
          @else
          <button type="button" wire:click="saveActivity" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
          @endif
        </div>

        @if (session()->has('successActivity'))
        <div class="p-3 bg-green-100 text-green-700 rounded mt-3">{{ session('successActivity') }}</div>
        @endif

        <!-- List Activitys -->
        <div class="mt-6">
          <label class="block text-sm font-medium mb-1">Daftar Activity</label>
          <div class="grid grid-cols-1 gap-4">
            @foreach($activitysList as $item)
            <div class="flex items-start gap-4 border rounded p-3 cursor-pointer hover:bg-slate-50" wire:click="editActivity({{ $item->id }})">
              <img src="{{ $item->image ? (Str::startsWith($item->image, ['http://','https://']) ? $item->image : Storage::url($item->image)) : asset('images/placeholder-80.svg') }}"
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

    </div>
  </div>

  <!-- Modal LFM (overlay, in-page) -->
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