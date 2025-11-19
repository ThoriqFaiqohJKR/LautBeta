<div class="p-4">
    <div class="max-w-5xl mx-auto py-4">
        <p class="text-lg font-semibold">Page About</p>
    </div>

    <div
        x-data="{
            lang: @entangle('lang'),
            saved: false,

            switchLang(to) {
                this.lang = to;
                // beritahu TinyMCE kalau bahasa berganti
                window.dispatchEvent(
                    new CustomEvent('about-switch-lang', {
                        detail: { lang: to }
                    })
                );
            }
        }"
        x-init="
            window.addEventListener('about-saved', () => {
                saved = true;
                setTimeout(() => saved = false, 1800);
            })
        "
        class="max-w-5xl mx-auto">
        {{-- Container 3 sisi --}}
        <div class="w-full flex bg-blue-700 shadow-lg border border-blue-800">
            {{-- Sisi kiri: ID (muncul saat EN aktif) --}}
            <div
                x-show="lang==='en'"
                x-transition
                @click="switchLang('id')"
                class="w-[6%] flex items-center justify-center cursor-pointer
                       text-white text-xs font-semibold py-6
                       bg-blue-700 hover:bg-blue-600">
                <span>ID</span>
            </div>

            {{-- Tengah: Card putih --}}
            <div class="flex-1 bg-white p-8">
                <h2 class="text-xl font-bold text-center mb-6">
                    About Content
                    <span class="text-sm font-normal text-slate-500">
                        ( <span class="uppercase" x-text="lang"></span> )
                    </span>
                </h2>

                <div class="space-y-6">
                    {{-- TITLE --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Title (<span class="uppercase" x-text="lang"></span>)
                        </label>

                        {{-- EN --}}
                        <input
                            type="text"
                            x-show="lang==='en'"
                            x-transition
                            wire:model.defer="titleEN"
                            class="w-full border px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm" />

                        {{-- ID --}}
                        <input
                            type="text"
                            x-show="lang==='id'"
                            x-transition
                            wire:model.defer="titleID"
                            class="w-full border px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm" />
                    </div>

                    {{-- DESCRIPTION / CONTENT (TinyMCE) --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Description (<span class="uppercase" x-text="lang"></span>)
                        </label>

                        <div
                            wire:ignore
                            x-data
                            data-base="{{ asset('tinymce') }}"
                            x-init="
                                const base = $el.dataset.base;

                                if (window.tinymce && tinymce.get('about_desc')) {
                                    tinymce.get('about_desc').remove();
                                }

                                tinymce.init({
                                    selector: '#about_desc',
                                    height: 320,
                                    base_url: base,
                                    suffix: '.min',
                                    license_key: 'gpl',
                                    plugins: 'advlist anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code fullscreen insertdatetime help preview',
                                    menubar: 'file edit view insert format tools table',
                                    toolbar: 'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code removeformat | fullscreen preview',
                                    promotion: false,
                                    branding: false,
                                    setup: (ed) => {
                                        // fungsi helper: sinkronkan isi editor dari Livewire
                                        const syncFromLivewire = (lang) => {
                                            const html = lang === 'id'
                                                ? @this.get('contentID')
                                                : @this.get('contentEN');

                                            ed.setContent(html || '');
                                        };

                                        // pertama kali editor nyala
                                        ed.on('init', () => {
                                            const currentLang = @this.get('lang');
                                            syncFromLivewire(currentLang);
                                        });

                                        // tiap blur -> kirim balik ke Livewire
                                        ed.on('blur', () => {
                                            const html = ed.getContent() || '';
                                            const currentLang = @this.get('lang');

                                            if (currentLang === 'id') {
                                                @this.set('contentID', html);
                                            } else {
                                                @this.set('contentEN', html);
                                            }
                                        });

                                        // dengarkan event switch bahasa dari Alpine
                                        window.addEventListener('about-switch-lang', e => {
                                            syncFromLivewire(e.detail.lang);
                                        });
                                    }
                                });
                            ">
                            <textarea id="about_desc"></textarea>
                        </div>
                    </div>

                    {{-- BUTTON SAVE + ALERT --}}
                    <div class="pt-4 border-t mt-4">
                        <button
                            type="button"
                            wire:click="save"
                            wire:loading.attr="disabled"
                            @click="saved = false"
                            class="px-6 py-2 bg-emerald-600 text-white hover:bg-emerald-700 text-sm">
                            <span wire:loading.remove wire:target="save">Save</span>
                            <span wire:loading wire:target="save">Menyimpan…</span>
                        </button>

                        <div
                            x-show="saved"
                            x-transition
                            class="mt-3 text-green-700 bg-green-100 px-4 py-2">
                            Berhasil disimpan.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sisi kanan: EN (muncul saat ID aktif) --}}
            <div
                x-show="lang==='id'"
                x-transition
                @click="switchLang('en')"
                class="w-[6%] flex items-center justify-center cursor-pointer
                       text-white text-xs font-medium py-6
                       bg-blue-700 hover:bg-blue-600">
                <span>EN</span>
            </div>
        </div>
    </div>
</div>