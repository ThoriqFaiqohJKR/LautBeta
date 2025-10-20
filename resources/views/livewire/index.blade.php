<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <main class="flex-grow">
        <!-- Hero Image -->
        <div class="relative w-full overflow-hidden shadow-lg">
            <img
                src="{{ asset('img/index.png') }}"
                class="w-full h-auto md:h-full object-contain md:object-cover md:object-top"
                alt="Index Image" />
        </div>


        <section class="max-w-6xl mx-auto py-10 px-4 sm:px-10  ">

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8 min-h-90 border">

                <!-- INSIGHT -->
                <div class="flex-[8]">
                    <h2 class="text-slate-500 text-sm font-semibold tracking-wider mb-3">INSIGHT</h2>


                    <div class="flex flex-wrap gap-4">
                        @foreach($insights as $item)
                        <article class="w-full md:w-1/2 lg:max-w-sm bg-[#bfbfbf] flex flex-col overflow-hidden min-h-90">

                            <!-- Gambar dibikin lebih tinggi -->
                            <div class="w-full aspect-[16/9] overflow-hidden">
                                @if(!empty($item['image']))
                                <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-slate-400 text-sm">No Image</span>
                                </div>
                                @endif
                            </div>

                            <!-- Isi teks -->
                            <div class="p-4 min-h-[140px] flex flex-col">
                                <p class="text-[#2a5fa0] font-semibold text-xl">
                                    {{ ucfirst(strtolower($item['type'])) }}
                                </p>
                                <p class="mt-1 text-xl text-white leading-snug line-clamp-3">
                                    {{ $item['title'] }}
                                </p>
                            </div>

                        </article>

                        @endforeach
                    </div>


                </div>

                <!-- EVENT -->
                <aside class="flex-[2] h-full self-stretch bg-white p-2">

                    <h2 class="text-slate-500 text-sm font-semibold tracking-wider mb-3">EVENT</h2>

                    @if(!empty($events))
                    @php($first = $events[0])
                    <a href="{{ $first['url'] }}" class="block group">
                        @if($first['image'])
                        <div class="w-full h-28 bg-white overflow-hidden shadow-sm">
                            <img src="{{ $first['image'] }}" alt="{{ $first['title'] }}" class="w-full h-full object-cover" />
                        </div>
                        @else
                        <div class="w-full aspect-[16/9] bg-gray-200 flex items-center justify-center">
                            <span class="text-slate-400 text-sm">No Image</span>
                        </div>
                        @endif
                        <p class="text-[#2a5fa0] font-semibold tracking-wide mt-4 text-base leading-snug">{{ $first['title'] }}</p>
                    </a>

                    <div class="mt-3 space-y-3">
                        @foreach(array_slice($events, 1) as $e)
                        <div class="border-b border-slate-300 pb-3">
                            <a href="{{ $e['url'] }}" class="block text-md leading-snug hover:text-[#2a5fa0]">
                                {{ $e['title'] }}
                            </a>

                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-slate-500">Belum ada event terpublish.</p>
                    @endif
                </aside>

            </div>
        </section>


        <section class="bg-[#5aa0b9] py-12 sm:py-16 px-4 sm:px-12 lg:px-48 flex flex-col lg:flex-row gap-8 sm:gap-12">
            @if($ngopini)
            <div class="flex flex-col lg:flex-row my-8 sm:my-16 gap-8 lg:gap-0">
                <div class="flex-shrink-0 w-full lg:w-3/6">
                    @if($ngopini['image'])
                    <img src="{{ $ngopini['image'] }}" alt="{{ $ngopini['title'] }}" class="w-full h-auto  shadow-lg" />
                    @else
                    <div class="w-full aspect-[16/9] bg-gray-200 flex items-center justify-center  shadow-lg">
                        <span class="text-slate-400 text-sm">No Image</span>
                    </div>
                    @endif
                </div>

                <div class="text-white lg:w-[38rem] px-0 sm:px-8 lg:px-24">
                    <p class="uppercase tracking-widest text-sm mb-2">NGOPINI</p>
                    <h3 class="text-2xl sm:text-3xl font-semibold mb-4">
                        {{ $ngopini['title'] }}
                    </h3>
                    <div class="lg:w-[20rem]">
                        <p class="text-sm mb-6">
                            @if($ngopini['date'])
                            <span class="font-semibold">{{ strtoupper($ngopini['date']) }}</span>
                            @endif
                            | {{ Str::limit($ngopini['desc'], 120) }}
                        </p>
                        <a href="{{ route('ngopini.detail', ['id' => $ngopini['id'], 'slug' => $ngopini['slug']]) }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold">
                            VIEW <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
            @else
            <p class="text-slate-400 text-center my-10">Belum ada Ngopini terpublish.</p>
            @endif

        </section>

        <section class="px-4 sm:px-12 lg:px-52 py-12 sm:py-24">
            <p>INFOGRAFIK</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 h-auto sm:h-screen">
                <!-- Kolom kiri (besar) -->
                <div class="col-span-1 sm:col-span-2 bg-gray-400 h-48 sm:h-auto"></div>

                <!-- Kolom kanan (2 kotak kecil tumpuk) -->
                <div class="flex flex-col gap-4">
                    <div class="bg-gray-400 h-48 sm:h-1/2"></div>
                    <div class="bg-gray-400 h-48 sm:h-1/2"></div>
                </div>
            </div>
        </section>
</div>
