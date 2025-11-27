<div class="max-w-4xl mx-auto p-6 space-y-6 bg-white shadow-md  ">

    <div class="space-y-1">
        <label class="font-semibold">Judul (ID)</label>
        <input type="text" wire:model="title_id" class="w-full border border-gray-300   p-2 focus:ring-2 focus:ring-blue-400">
    </div>

    <div>
        <label class="font-semibold">Judul (EN)</label>
        <input type="text" wire:model="title_en" class="w-full border border-gray-300   p-2 focus:ring-2 focus:ring-blue-400">
    </div>

    <div class="space-y-1">
        <label class="font-semibold">Deskripsi (ID)</label>
        <div
            wire:ignore
            wire:key="desc-id"
            x-data
            data-base="/tinymce"
            data-initial="{{ $description_id ?? '' }}"
            x-init="
                const base=$el.dataset.base, initial=$el.dataset.initial||'';
                if(window.tinymce && tinymce.get('desc_id_editor')) tinymce.get('desc_id_editor').remove();

                tinymce.init({
                    selector:'#desc_id_editor',
                    height:300,
                    min_height:200,
                    max_height:480,
                    base_url:base,
                    suffix:'.min',
                    license_key:'gpl',
                    plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
                    menubar:false,
                    toolbar:'undo redo | image | bold italic underline | bullist numlist | alignleft aligncenter alignright ',
                    toolbar_mode:'sliding',
                    branding:false,
                    promotion:false,
                    forced_root_block:'p',

                    file_picker_types:'image',
                    file_picker_callback:(cb,value,meta)=>{
                        if(meta.filetype!=='image') return;
                        const routePrefix='/laravel-filemanager?type=image';
                        const x=window.innerWidth*0.8;
                        const y=window.innerHeight*0.8;
                        const lfm=window.open(routePrefix,'FileManager',`width=${x},height=${y},left=100,top=100`);
                        window.SetUrl=(items)=>{
                            const file=items[0];
                            cb(file.url, { alt: file.name });
                            lfm.close();
                        };
                    },

                    setup:(ed)=>{
                        ed.on('init',()=> ed.setContent(initial));
                        ed.on('change keyup undo redo',()=>{
                            @this.set('description_id', ed.getContent());
                        });
                    }
                });
            ">
            <textarea id="desc_id_editor"></textarea>
        </div>
    </div>

    <div class="space-y-1">
        <label class="font-semibold">Deskripsi (EN)</label>
        <div
            wire:ignore
            wire:key="desc-en"
            x-data
            data-base="/tinymce"
            data-initial="{{ $description_en ?? '' }}"
            x-init="
                const base=$el.dataset.base, initial=$el.dataset.initial||'';
                if(window.tinymce && tinymce.get('desc_en_editor')) tinymce.get('desc_en_editor').remove();

                tinymce.init({
                    selector:'#desc_en_editor',
                    height:300,
                    min_height:200,
                    max_height:480,
                    base_url:base,
                    suffix:'.min',
                    license_key:'gpl',
                    plugins:'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
                    menubar:false,
                    toolbar:'undo redo | image | bold italic underline | bullist numlist | alignleft aligncenter alignright ',
                    toolbar_mode:'sliding',
                    branding:false,
                    promotion:false,
                    forced_root_block:'p',

                    setup:(ed)=>{
                        ed.on('init',()=> ed.setContent(initial));
                        ed.on('change keyup undo redo',()=>{
                            @this.set('description_en', ed.getContent());
                        });
                    }
                });
            ">
            <textarea id="desc_en_editor"></textarea>
        </div>
    </div>



    <div x-data="{
        preview: [],

        addPreview(files) {
            [...files].forEach(file => {
                let reader = new FileReader();
                reader.onload = e => this.preview.push(e.target.result);
                reader.readAsDataURL(file);
            });
        },

        removePreview(i) {
            this.preview.splice(i, 1);
        }
    }">

        <p class="font-semibold mb-1">Masukkan Gambar</p>

        <input type="file"
            wire:model="newImages"
            multiple
            class="border p-2 w-62 mb-3"
            @change="addPreview($event.target.files)">

        <div class="flex flex-wrap gap-3 mt-3">
            <template x-for="(img, i) in preview" :key="i">
                <div class="relative w-32 aspect-[4/5] bg-gray-200 overflow-hidden rounded">
                    <img :src="img" class="w-full h-full object-cover">

                    <button
                        @click="removePreview(i)"
                        class="absolute top-1 right-1 bg-red-600 text-white text-xs px-1">
                        X
                    </button>
                </div>
            </template>
        </div>

    </div>


    <div>
        <label>Status</label>
        <select wire:model="publikasi" class="border border-gray-300 p-2  focus:ring-2 focus:ring-blue-400">
            <option value="draft">Draft</option>
            <option value="publish">Publish</option>
        </select>
    </div>

    <div>
        <label>Tanggal Publikasi</label>
        <input type="date" wire:model="tanggal_publikasi" class="border border-gray-300 p-2  focus:ring-2 focus:ring-blue-400">
    </div>

    <button wire:click="save" class="px-5 py-2.5 bg-blue-600 text-white  shadow hover:bg-blue-700 transition">
        Simpan
    </button>

</div>
