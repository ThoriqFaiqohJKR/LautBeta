<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div>

        <div>

            @if($imagePreview)
            <div class="w-full aspect-[3/1]">
                <img src="{{ $imagePreview }}" alt="{{ $title_en ?? $title_id ?? 'Insight' }}" class="w-full h-full object-cover" />
            </div>
            @endif

            <div class="px-4 sm:px-6 lg:max-w-3xl mx-auto py-12">
                <div class="gap-2 flex mb-4">
                    <a href="{{ route('cms.page.index.agenda', ['locale' => app()->getLocale()]) }}">
                        <p class="text-xl hover:underline">Page Agenda </p>
                    </a>
                    <p> > </p>
                    <p class="text-xl text-blue-700">Preview Agenda</p>
                </div>

                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-6 leading-snug">
                    {{ $lang==='id' ? ($title_id ?? 'Tanpa Judul') : ($title_en ?? 'Untitled') }}
                </h1>


                <div class="text-center text-sm text-slate-500 mb-8">
                    <div class="flex gap-2 mb-6">
                        <button type="button"
                            class="px-3 py-1 rounded border {{ $lang==='en' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                            wire:click="setLang('en')">EN</button>
                        <button type="button"
                            class="px-3 py-1 rounded border {{ $lang==='id' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}"
                            wire:click="setLang('id')">ID</button>
                    </div>

                    <span>{{ $tanggal_publikasi ? \Carbon\Carbon::parse($tanggal_publikasi)->translatedFormat('d F Y') : '—' }}</span>
                    <span class="mx-2">•</span>
                    <span class="capitalize">{{ $type }}</span>
                    <span class="mx-2">•</span>
                    <span class="capitalize">{{ $publikasi }}</span>
                </div>


                <div class="prose max-w-none text-gray-700 mb-12 text-justify">
                    {!! $lang==='id' ? ($content_id ?? $description_id ?? '') : ($content_en ?? $description_en ?? '') !!}
                </div>



            </div>
        </div>
    </div>
</div>
