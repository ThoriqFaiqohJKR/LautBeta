<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div>
        <div class="px-4 sm:px-6 lg:px-4 py-4 sm:py-24 max-w-6xl mx-auto">
            <h1 class="text-slate-600 text-sm tracking-widest mb-4">{{ __('Fitur') }}</h1>

            @forelse($items as $item)
            <a href="{{ route('feature.detail', ['id' => $item['id'], 'slug' => $item['slug']]) }}"
                class="block max-w-5xl hover:bg-slate-50 transition">
                <div class="flex flex-col md:flex-row py-4 border-b-2 md:mr-40 items-end group transition-all duration-300 hover:bg-slate-100/10">
                    <div class="overflow-hidden  md:w-1/2">
                        <img
                            src="{{ $item['image_url'] }}"
                            alt="{{ $item['title'] }}"
                            class="w-full h-48 md:h-56 object-cover transform transition-transform duration-500 group-hover:scale-105"
                            loading="lazy" />
                    </div> 

                    <div class="flex flex-col mt-4 md:mt-0 md:flex-1 px-10">
                        <h2 class="md:text-xl leading-relaxed font-semibold mt-1 group-hover:text-[#2a5fa0] transition-colors duration-300">
                            {{ $item['title'] }}
                        </h2>
                    </div>
                </div>
            </a>
            @empty
            <p class="text-slate-500">{{ __('Belum ada data feature.') }}</p>
            @endforelse
        </div>
    </div>


</div> 