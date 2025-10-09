<div>
    <div>
        @if($imagePreview)
        <div class="w-full aspect-[3/1]">
            <img src="{{ $imagePreview }}" alt="{{ $title_en ?? $title_id ?? 'Insight' }}" class="w-full h-full object-cover" />
        </div>
        @endif

        <div class="px-4 sm:px-6 lg:max-w-2xl mx-auto py-12">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-6 leading-snug">
                {{ $lang==='id' ? ($title_id ?? 'Tanpa Judul') : ($title_en ?? 'Untitled') }}
            </h1>

            <div class="text-center text-sm text-slate-500 mb-8">
                <span>{{ $tanggal_publikasi ? \Carbon\Carbon::parse($tanggal_publikasi)->translatedFormat('d F Y') : '—' }}</span>
                <span class="mx-2">•</span>
                <span class="capitalize">{{ $type }}</span>
                <span class="mx-2">•</span>
                <span class="capitalize">{{ $publikasi }}</span>
            </div>

            <div class="prose max-w-none text-gray-700 mb-12 text-justify">
                {!! $content !!}
            </div>
        </div>
    </div>
</div>