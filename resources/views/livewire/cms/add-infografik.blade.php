<div class="max-w-4xl mx-auto p-6 space-y-6 bg-white shadow-md" x-data="{ lang: 'id' }">
    <div class="flex gap-2 mb-4">
        <button @click="lang='id'" :class="lang=='id' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-3 py-1 rounded">Indonesia</button>
        <button @click="lang='en'" :class="lang=='en' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-3 py-1 rounded">English</button>
    </div>

    <div>
        <label class="font-semibold">Judul</label>
        <input type="text" wire:model="title_id" x-show="lang=='id'" class="w-full border p-2">
        <input type="text" wire:model="title_en" x-show="lang=='en'" class="w-full border p-2">
    </div>

    <div wire:ignore x-show="lang=='id'"
        x-init="
            if (tinymce.get('desc_editor_id')) tinymce.get('desc_editor_id').remove();
            tinymce.init({
                selector:'#desc_editor_id',
                height:300,
                base_url:'/tinymce',
                suffix:'.min',
                license_key:'gpl',
                menubar:false,
                plugins:'advlist anchor autolink link lists image table code preview',
                toolbar:'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright',
                setup:(ed)=>{
                    ed.on('init',()=> ed.setContent(@this.get('description_id') || ''));
                    ed.on('change keyup undo redo',()=>{@this.set('description_id', ed.getContent())});
                }
            });
        ">
        <textarea id="desc_editor_id"></textarea>
    </div>

    <div wire:ignore x-show="lang=='en'"
        x-init="
            if (tinymce.get('desc_editor_en')) tinymce.get('desc_editor_en').remove();
            tinymce.init({
                selector:'#desc_editor_en',
                height:300,
                base_url:'/tinymce',
                suffix:'.min',
                license_key:'gpl',
                menubar:false,
                plugins:'advlist anchor autolink link lists image table code preview',
                toolbar:'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright',
                setup:(ed)=>{
                    ed.on('init',()=> ed.setContent(@this.get('description_en') || ''));
                    ed.on('change keyup undo redo',()=>{@this.set('description_en', ed.getContent())});
                }
            });
        ">
        <textarea id="desc_editor_en"></textarea>
    </div>


    <!-- IMAGE UPLOAD -->
    <div x-data="{
        preview: [],
        add(files) {
            [...files].forEach(file => {
                let r = new FileReader();
                r.onload = e => this.preview.push(e.target.result);
                r.readAsDataURL(file);
            });
        },
        remove(i) { this.preview.splice(i,1); }
    }" class="space-y-2">

        <p class="font-semibold">Masukkan Gambar</p>

        <input type="file" wire:model="newImages" multiple class="border p-2 w-62 mb-3" @change="add($event.target.files)">

        <div class="flex flex-wrap gap-3 mt-3">
            <template x-for="(img,i) in preview" :key="i">
                <div class="relative w-32 aspect-[4/5] bg-gray-200 overflow-hidden rounded">
                    <img :src="img" class="w-full h-full object-cover">
                    <button @click="remove(i)" class="absolute top-1 right-1 bg-red-600 text-white text-xs px-1">X</button>
                </div>
            </template>
        </div>
    </div>

    <!-- STATUS -->
    <div>
        <label class="font-semibold">Status</label>
        <select wire:model="publikasi" class="border p-2 w-full">
            <option value="draft">Draft</option>
            <option value="publish">Publish</option>
        </select>
    </div>

    <!-- DATE -->
    <div>
        <label class="font-semibold">Tanggal Publikasi</label>
        <input type="date" wire:model="tanggal_publikasi" class="border p-2 w-full">
    </div>

    <!-- SAVE BUTTON -->
    <button wire:click="save" class="px-5 py-2.5 bg-blue-600 text-white shadow hover:bg-blue-700 rounded">
        Simpan
    </button>

</div>