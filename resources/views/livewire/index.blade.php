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

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8 min-h-90 ">

                <!-- INSIGHT -->
                <div class="flex-[8]">
                    <h2 class="text-slate-500 text-sm font-semibold tracking-wider mb-3">INSIGHT</h2>


                    <div class="flex flex-wrap gap-4">
                        @foreach($insights as $item)

                        <article class="relative  w-full md:w-1/2 lg:max-w-sm bg-[#bfbfbf] flex flex-col overflow-hidden min-h-90">

                            <a href="{{ $item['url'] }}" class="block group">
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
                                <div class="p-4  min-h-[140px] flex flex-col">
                                    <p class="text-[#2a5fa0]  text-md">
                                        {{ ucfirst(strtolower($item['type'])) }}
                                    </p>
                                    <p class="mt-1 text-lg text-white leading-snug line-clamp-3">
                                        {{ $item['title'] }}
                                    </p>
                                    <span class="absolute right-0 bottom-0 w-0 h-0 border-l-[28px] border-b-[28px] border-l-transparent border-b-gray-500"></span>
                                </div>
                            </a>
                        </article>

                        @endforeach
                    </div>


                </div>

                <!-- EVENT -->
                <div class="flex-[2] h-full bg-white flex flex-col">

                    <h2 class="text-slate-500 text-sm font-semibold tracking-wider mb-3">AGENDA</h2>

                    @if(!empty($events))
                    <div class="flex flex-col justify-between flex-1  p-3">

                        <!-- EVENT PERTAMA -->
                        <div>
                            @php($first = $events[0])
                            <a href="{{ $first['url'] }}" class="block group">
                                <div class="w-full h-full overflow-hidden shadow-sm ">
                                    <img src="{{ $first['image'] }}"
                                        class="w-full h-full object-contain hover:scale-100 transition duration-200">
                                </div>
                                <p class="text-[#2a5fa0] font-semibold tracking-wide text-base leading-snug border-b border-gray-300 py-2">
                                    {{ $first['title'] }}
                                </p>
                            </a>
                        </div>

                        <!-- EVENT LIST (TURUN KE BAWAH & TIDAK MELEBIHI KOTAK) -->
                        <div class="space-y-3">
                            @foreach(array_slice($events, 1) as $e)
                            <div class=" mb-8">
                                <a href="{{ $e['url'] }}" class="block text-md leading-snug hover:text-[#2a5fa0] py-2">
                                    <p>event Sebelumnya event sebelumnya</p>

                                </a>
                            </div>
                            @endforeach
                        </div>


                    </div>
                    @endif

                </div>


            </div>
        </section>


        <section class="bg-[#5aa0b9] py-12 sm:py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-12 flex flex-col lg:flex-row gap-8 sm:gap-12">
                @if($ngopini)
                <div class="flex flex-col lg:flex-row my-8 sm:my-16 gap-24 lg:gap-18">
                    <div class="flex-shrink-0 w-full lg:w-3/6">
                        @if($ngopini['image'])
                        <div class="max-w-lg aspect-[16/9] overflow-hidden shadow-md ">
                            <img src="{{ $ngopini['image'] }}" alt="{{ $ngopini['title'] }}"
                                class="w-full h-full object-contain">
                        </div>

                        @else
                        <div class="w-full aspect-[16/9] bg-gray-200 flex items-center justify-center  shadow-lg">
                            <span class="text-slate-400 text-sm">No Image</span>
                        </div>
                        @endif
                    </div>

                    <div class="text-white max-w-xs lg:max-w-sm flex flex-col ">
                        <p class="uppercase tracking-widest text-sm mb-2">NGOPINI</p>

                        <h3 class="text-xl sm:text-2xl mb-4">
                            {{ $ngopini['title'] }}
                        </h3>

                        <p class="text-sm mb-6 break-all md:break-words">
                            @if($ngopini['date'])
                            <span class="font-semibold">{{ strtoupper($ngopini['date']) }}</span>
                            @endif
                            | {{ Str::limit($ngopini['desc'], 120) }}
                        </p>


                        <!-- VIEW tetap di bawah -->
                        <a href="{{ route('ngopini.detail', ['id' => $ngopini['id'], 'slug' => $ngopini['slug']]) }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold mt-auto hover:underline">
                            VIEW <span>&rarr;</span>
                        </a>
                    </div>

                </div>
                @else
                <p class="text-slate-400 text-center my-10">Belum ada Ngopini terpublish.</p>
                @endif
            </div>

        </section>

        <section class="px-4 sm:px-12 lg:px-52 py-24">
            <p class="text-sm tracking-widest text-slate-600 mb-4">INFOGRAFIK</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">


                @if(isset($infographics[0]))
                <div class="col-span-1 sm:col-span-2"
                    x-data="{
        idx: 0,
        open: false,
        popupIdx: 0,
        imgs: {{ json_encode($infographics[0]['images']) }}
    }">

                    <!-- MAIN IMAGE -->
                    <div class="w-full aspect-[4/5] bg-gray-200 overflow-hidden relative">

                        <img :src="imgs[idx]"
                            class="w-full h-full object-cover cursor-pointer"
                            @click="open = true; popupIdx = idx">

                        <!-- PREV -->
                        <button
                            x-show="idx > 0"
                            @click.stop="idx--"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-3 py-2">
                            ‹
                        </button>

                        <!-- NEXT -->
                        <button
                            x-show="idx < imgs.length - 1"
                            @click.stop="idx++"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white px-3 py-2">
                            ›
                        </button>

                    </div>

                    <!-- POPUP -->
                    <div x-show="open" x-cloak
                        class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-6"
                        @click.self="open = false">

                        <div class="relative max-w-4xl w-full flex justify-center"
                            x-data="{
            startX: 0,
            endX: 0,
            handleTouchStart(e) { this.startX = e.touches[0].clientX },
            handleTouchEnd(e) {
                this.endX = e.changedTouches[0].clientX;
                let diff = this.endX - this.startX;
                if (Math.abs(diff) > 50) {
                    if (diff < 0) popupIdx = (popupIdx + 1) % imgs.length;
                    else popupIdx = (popupIdx - 1 + imgs.length) % imgs.length;
                }
            }
        }"
                            @touchstart="handleTouchStart($event)"
                            @touchend="handleTouchEnd($event)">

                            <!-- WRAPPER GAMBAR -->
                            <div class="relative py-6">

                                <!-- GAMBAR (NO ROUNDED) -->
                                <img
                                    :src="imgs[popupIdx]"
                                    class="max-w-full max-h-[80vh] object-contain">

                                <!-- BUTTON KIRI (desktop only) -->
                                <button
                                    @click.stop="popupIdx = (popupIdx - 1 + imgs.length) % imgs.length"
                                    class="hidden sm:flex absolute left-2 top-1/2 -translate-y-1/2
           bg-black/70 hover:bg-black/80 text-white p-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5">
                                        <path stroke="white" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>

                                <button
                                    @click.stop="popupIdx = (popupIdx + 1) % imgs.length"
                                    class="hidden sm:flex absolute right-2 top-1/2 -translate-y-1/2
           bg-black/70 hover:bg-black/80 text-white p-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2">
                                        <path stroke="white" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>


                            </div>
                        </div>
                    </div>


                </div>


                @endif


                {{-- ================== KANAN (2 & 3) ================== --}}
                <div class="flex flex-col gap-4">

                    {{-- INFOGRAFIK 2 --}}
                    @if(isset($infographics[1]))
                    <div x-data="{ idx: 0, imgs: {{ json_encode($infographics[1]['images']) }} }">

                        <div class="w-full aspect-[4/5] bg-gray-200  overflow-hidden relative">


                            <img :src="imgs[idx]" class="w-full h-full object-cover">

                            <!-- PREV -->
                            <button @click="idx = (idx - 1 + imgs.length) % imgs.length"
                                class="absolute left-2 top-1/2 -translate-y-1/2
                        bg-black/40 text-white px-2 py-1  text-sm">
                                ‹
                            </button>

                            <!-- NEXT -->
                            <button @click="idx = (idx + 1) % imgs.length"
                                class="absolute right-2 top-1/2 -translate-y-1/2
                        bg-black/40 text-white px-2 py-1  text-sm">
                                ›
                            </button>

                        </div>


                    </div>
                    @endif


                    {{-- INFOGRAFIK 3 --}}
                    @if(isset($infographics[2]))
                    <div x-data="{ idx: 0, imgs: {{ json_encode($infographics[2]['images']) }} }">

                        <div class="w-full aspect-[4/5] bg-gray-200  overflow-hidden relative">


                            <img :src="imgs[idx]" class="w-full h-full object-cover">

                            <!-- PREV -->
                            <button @click="idx = (idx - 1 + imgs.length) % imgs.length"
                                class="absolute left-2 top-1/2 -translate-y-1/2
                        bg-black/40 text-white px-2 py-1  text-sm">
                                ‹
                            </button>

                            <!-- NEXT -->
                            <button @click="idx = (idx + 1) % imgs.length"
                                class="absolute right-2 top-1/2 -translate-y-1/2
                        bg-black/40 text-white px-2 py-1  text-sm">
                                ›
                            </button>

                        </div>


                    </div>
                    @endif

                </div>

            </div>
        </section>





</div>
