<div>
    {{-- Success is as dangerous as failure. --}}
    <div>
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-24 max-w-6xl mx-auto">
            <h1 class="text-slate-600 text-sm tracking-widest">{{ __('INSIGHT') }}</h1>

            @forelse($items as $item)
            <a href="{{ route('analysis.detail', ['id' => $item['id'], 'slug' => $item['slug']]) }}"
                class="block max-w-5xl  transition">
                <div class="flex flex-col md:flex-row gap-4 md:gap-12 py-4 border-b-1 border-[#2a5fa0] md:mr-40 items-start md:items-start group transition-all duration-300 hover:bg-slate-100/10">
                    <div class="overflow-hidden md:w-1/2 w-full">
                        <img
                            src="{{ $item['image_url'] }}"
                            alt="{{ $item['title'] }}"
                            class="w-full h-48 md:h-56 object-cover transform transition-transform duration-500 "
                            loading="lazy" />
                    </div>

                    <div class="flex flex-col mt-2 md:mt-0 md:flex-1">
                        <p class="text-[#2a5fa0] ">{{ __('ANALYSIS') }}</p>
                        <h2 class="md:text-xl leading-snug font-semibold mt-1 transition-colors duration-300">
                            {{ $item['title'] }}
                        </h2>
                    </div>
                </div>
            </a>

            @empty
            <p class="text-slate-500">{{ __('Belum ada data Analysis.') }}</p>
            @endforelse
        </div>
    </div>


</div>