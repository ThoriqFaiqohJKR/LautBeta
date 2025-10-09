<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div>
        <div class="w-full aspect-[3/1] overflow-hidden">
            <img src="{{ $item['image_url'] ?? asset('img/news.png') }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover object-center" />
        </div>

        <div class="px-4 sm:px-6 lg:max-w-2xl mx-auto py-10">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-semibold text-center mb-4">
                {{ $item['title'] }}
            </h1>

            <div class="flex items-center justify-center gap-3 text-sm text-slate-600 mb-8">
                <span>
                    {{ $item['tanggal_publikasi'] ? \Illuminate\Support\Carbon::parse($item['tanggal_publikasi'])->format('Y') : '—' }}
                </span>
                <span>•</span>
                <span class="uppercase">
                    {{ $item['publikasi'] }}
                </span>
            </div>

            <div class="prose mx-auto text-gray-700">
                {!! $item['content'] !!}
            </div>
        </div>
    </div>

</div>