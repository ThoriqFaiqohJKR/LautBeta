<div class="p-4">
    <div x-data="{
  lang:@entangle('lang'),
  en:@entangle('contentEN'),
  id:@entangle('contentID'),
  set(c){window.dispatchEvent(new CustomEvent('setEditor',{detail:{content:c||''}}))}
}"
        class="max-w-4xl mx-auto p-4 border bg-white space-y-6">

        <div class="flex items-center gap-2 mb-6 ">
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



        <div class="mb-5">
            <label class="block text-sm font-medium text-slate-700 mb-2">Title (<span class="uppercase" x-text="lang.toUpperCase()"></span>)</label>
            <input type="text" wire:model.defer="titleEN" x-show="lang==='en'" class="w-full border px-3 py-2 focus:ring-2 focus:ring-emerald-500" />
            <input type="text" wire:model.defer="titleID" x-show="lang==='id'" class="w-full border px-3 py-2 focus:ring-2 focus:ring-emerald-500" />
            @error('titleEN') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            @error('titleID') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-5">
            <label class="block text-sm font-medium text-slate-700 mb-2">Description (<span class="uppercase" x-text="lang.toUpperCase()"></span>)</label>

            <!-- TinyMCE -->
            <div wire:ignore
                x-data
                data-base="{{ asset('tinymce') }}"
                data-initial="{{ $lang==='id' ? ($contentID ?? '') : ($contentEN ?? '') }}"
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
                {title:'Text Styles',items:[
                  {title:'Paragraph',format:'p'},
                  {title:'Headings',items:[
                    {title:'H1',format:'h1'},{title:'H2',format:'h2'},{title:'H3',format:'h3'},
                    {title:'H4',format:'h4'},{title:'H5',format:'h5'},{title:'H6',format:'h6'}
                  ]},
                  {title:'Inline',items:[
                    {title:'Bold',inline:'b'},{title:'Italic',inline:'i'},
                    {title:'Underline',inline:'u'},{title:'Strikethrough',inline:'strike'}
                  ]},
                  {title:'Fonts',items:[
                    {title:'Sans-serif',inline:'span',styles:{'font-family':'Arial,Helvetica,sans-serif'}},
                    {title:'Serif',inline:'span',styles:{'font-family':'Times New Roman,serif'}},
                    {title:'Monospace',inline:'span',styles:{'font-family':'Courier New,monospace'}}
                  ]}
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
                  if (@this.get('lang') === 'id') {
                    @this.set('contentID', html);
                  } else {
                    @this.set('contentEN', html);
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

                          init_instance_callback:(editor)=>{
                const handle=editor.getContainer().querySelector('.tox-statusbar__resize-handle');
                if(handle){
                  handle.style.marginLeft='auto';
                  handle.style.marginRight='4px';
                  handle.style.cursor='se-resize';
                }
              }

            });


            window.addEventListener('setEditor', (e) => {
              const ed = tinymce.get('about_desc');
              if (ed) ed.setContent(e.detail?.content || '');
            });
           ">
                <textarea id="about_desc"></textarea>
            </div>

            @error('contentEN') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            @error('contentID') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
        </div>

        <div class="mt-6">
            <button type="button" wire:click="save" wire:loading.attr="disabled" class="px-6 py-2 bg-emerald-600 text-white hover:bg-emerald-700">
                <span wire:loading.remove wire:target="save">Save</span>
                <span wire:loading wire:target="save">Menyimpan…</span>
            </button>
            <div x-show="saved" x-transition class="mt-3 text-green-700 bg-green-100 px-4 py-2">Berhasil disimpan.</div>
        </div>
    </div>
</div>
