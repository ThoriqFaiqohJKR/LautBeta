<div class="p-4">
    <div x-data="{
    lang:@entangle('lang'),
    en:@entangle('contentEN'),
    id:@entangle('contentID'),
    saved:false,
    set(c){window.dispatchEvent(new CustomEvent('setEditor',{detail:{content:c||''}}))}
  }"
        x-init="window.addEventListener('saved',()=>{saved=true;setTimeout(()=>saved=false,1800)})"
        class="max-w-4xl mx-auto p-4 border bg-white space-y-6">

        <div class="flex items-center gap-2 mb-6">
            <button type="button"
                @click="lang='en'; set(en)"
                class="px-4 py-2 border"
                :class="{'bg-emerald-600 text-white':lang==='en','bg-gray-100 text-gray-700':lang!=='en'}">
                ENGLISH
            </button>
            <button type="button"
                @click="lang='id'; set(id)"
                class="px-4 py-2 border"
                :class="{'bg-emerald-600 text-white':lang==='id','bg-gray-100 text-gray-700':lang!=='id'}">
                INDONESIA
            </button>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Title (<span class="uppercase" x-text="lang.toUpperCase()"></span>)
            </label>
            <input type="text" wire:model.defer="titleEN" x-show="lang==='en'" class="w-full border px-3 py-2 focus:ring-2 focus:ring-emerald-500" />
            <input type="text" wire:model.defer="titleID" x-show="lang==='id'" class="w-full border px-3 py-2 focus:ring-2 focus:ring-emerald-500" />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Description (<span class="uppercase" x-text="lang.toUpperCase()"></span>)
            </label>
            <div wire:ignore x-data data-base="{{ asset('tinymce') }}" x-init="
        const base = $el.dataset.base;
        if (window.tinymce && tinymce.get('about_desc')) tinymce.get('about_desc').remove();
        tinymce.init({
          selector:'#about_desc',
          height:300,
          base_url:base,
          suffix:'.min',
          license_key:'gpl',
          plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
          menubar:'file edit view insert format tools table',
          toolbar:'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code removeformat | fullscreen preview',
          promotion:false,
          branding:false,
          setup:(ed)=>{
            ed.on('init',()=>{ed.setContent($wire.get('lang')==='id'?$wire.get('contentID'):$wire.get('contentEN'))});
            ed.on('blur',()=>{
              const html=ed.getContent();
              if($wire.get('lang')==='id'){$wire.set('contentID',html);}
              else{$wire.set('contentEN',html);}
            });
          }
        });
        window.addEventListener('setEditor',e=>{
          const ed=tinymce.get('about_desc');
          if(ed)ed.setContent(e.detail?.content||'');
        });
      ">
                <textarea id="about_desc"></textarea>
            </div>
        </div>

        <div class="mt-6" x-data="{ saved:false }" x-on:about-saved.window="saved=true; setTimeout(()=>saved=false,1800)">
            <button type="button" wire:click="save" wire:loading.attr="disabled"
                class="px-6 py-2 bg-emerald-600 text-white hover:bg-emerald-700">
                <span wire:loading.remove wire:target="save">Save</span>
                <span wire:loading wire:target="save">Menyimpan…</span>
            </button>

            <div x-show="saved" x-transition
                class="mt-3 text-green-700 bg-green-100 px-4 py-2 rounded">
                Berhasil disimpan.
            </div>
        </div>

    </div>
</div>
