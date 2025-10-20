<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div>
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-24 max-w-6xl mx-auto">
            <h1 class="text-slate-600 text-sm tracking-widest mb-4">{{ __('Report') }}</h1>

            @forelse($items as $item)
            <a href="{{ route('report.detail', ['id' => $item['id'], 'slug' => $item['slug']]) }}"
                class="block max-w-5xl hover:bg-slate-50 transition">
                <div class="flex flex-col md:flex-row md:gap-12 py-4 border-b-2 md:mr-40 items-end">
                    <img src="{{ $item['image_url'] }}"
                        alt="{{ $item['title'] ?? 'Report Image' }}"
                        class="object-cover aspect-[16/9] w-full md:w-1/2"
                        loading="lazy">

                    <div class="flex flex-col mt-4 md:mt-0">
                        <h2 class="md:text-xl leading-relaxed font-semibold mt-1">{{ $item['title'] }}</h2>
                    </div>
                </div>
            </a>
            @empty
            <p class="text-slate-500">{{ __('Belum ada data  Report.') }}</p>
            @endforelse
        </div>
    </div>

</div>
