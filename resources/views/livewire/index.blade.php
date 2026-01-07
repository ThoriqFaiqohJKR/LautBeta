<div
    x-data="{
        popup:false,
        popupImgs:[],
        popupIdx:0,
        popupTitle:'',
        popupDesc:'',

        openPopup(imgs, index){
            this.popupImgs = imgs
            this.popupIdx  = index
            this.popup     = true
            document.documentElement.classList.add('overflow-hidden')
        },

        closePopup(){
            this.popup = false
            document.documentElement.classList.remove('overflow-hidden')
        }
    }">

    <main class="flex-grow">

        <div class="relative w-full aspect-[2.5/1] bg-white flex items-center justify-center overflow-hidden">

            <img
                src="{{ asset('img/index.jpg') }}"
                class="w-full h-full object-contain md:object-cover object-top"
                alt="Index Image" />

            <div class="absolute inset-0 flex items-center justify-center px-6 text-white">
                <div class="max-w-3xl text-center text-xl font-semibold">
                    <p>Situs ini akan fokus pada isu-isu pesisir, laut, dan pulau-pulau kecil serta berbagai dinamikanya.</p>
                </div>
            </div>

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
        <!-- INFOGRAFIK -->
        <section class="px-4 sm:px-12 lg:px-52 py-24">

            <p class="text-sm tracking-widest text-slate-600 mb-4">INFOGRAFIK</p>

            <div class="flex flex-col sm:flex-row gap-6 justify-center">

                <!-- ITEM 0 -->
                <div class="max-w-lg w-full sm:w-auto">
                    <div
                        class="w-full aspect-[4/5] bg-gray-300 overflow-hidden relative"
                        x-data="{ idx: 0, imgs: {{ json_encode($infographics[0]['images']) }} }">

                        <!-- Gambar -->
                        <img
                            :src="imgs[idx]"
                            class="w-full h-full object-cover cursor-pointer"
                            @click="openPopup(imgs, idx)">

                        <!-- PREV -->
                        <button
                            x-show="idx > 0"
                            @click.stop="idx--"
                            class="absolute left-1 top-1/2 -translate-y-1/2 bg-black/70 p-1 rounded-full text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="white"
                                class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- NEXT -->
                        <button
                            x-show="idx < imgs.length - 1"
                            @click.stop="idx++"
                            class="absolute right-1 top-1/2 -translate-y-1/2 bg-black/70 p-1 rounded-full text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="white"
                                class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                    </div>
                </div>


                <!-- ITEM 1 & 2 -->
                <div class="flex flex-col gap-5 w-full sm:w-auto">

                    <!-- ITEM 1 -->
                    @if(isset($infographics[1]))
                    <div
                        class="w-full sm:w-62 aspect-[4/5] bg-gray-300 overflow-hidden relative"
                        x-data="{ idx: 0, imgs: {{ json_encode($infographics[1]['images']) }} }">

                        <img
                            :src="imgs[idx]"
                            class="w-full h-full object-cover cursor-pointer"
                            @click="openPopup(imgs, idx)">

                        <!-- PREV -->
                        <button
                            x-show="idx > 0"
                            @click.stop="idx--"
                            class="absolute left-1 top-1/2 -translate-y-1/2 bg-black/70 p-1 rounded-full text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="white"
                                class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- NEXT -->
                        <button
                            x-show="idx < imgs.length - 1"
                            @click.stop="idx++"
                            class="absolute right-1 top-1/2 -translate-y-1/2 bg-black/70 p-1 rounded-full text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="white"
                                class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                    </div>
                    @endif


                    <!-- ITEM 2 -->
                    @if(isset($infographics[2]))
                    <div
                        class="w-full sm:w-62 aspect-[4/5] bg-gray-300 overflow-hidden relative"
                        x-data="{ idx: 0, imgs: {{ json_encode($infographics[2]['images']) }} }">

                        <img
                            :src="imgs[idx]"
                            class="w-full h-full object-cover cursor-pointer"
                            @click="openPopup(imgs, idx)">

                        <!-- PREV -->
                        <button
                            x-show="idx > 0"
                            @click.stop="idx--"
                            class="absolute left-1 top-1/2 -translate-y-1/2 bg-black/70 p-1 rounded-full text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="white"
                                class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- NEXT -->
                        <button
                            x-show="idx < imgs.length - 1"
                            @click.stop="idx++"
                            class="absolute right-1 top-1/2 -translate-y-1/2 bg-black/70 p-1 rounded-full text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="white"
                                class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                    </div>
                    @endif

                </div>

            </div>

            <!-- POPUP -->
            <div
                x-data="{
            popup: true,
            infoIdx: 0,
            popupIdx: 0,
            infographics: infographicsData,
            get popupImgs() {
                return this.infographics[this.infoIdx].images
            },
            get popupTitle() {
                return this.infographics[this.infoIdx].title
            },
            get popupDesc() {
                return this.infographics[this.infoIdx].desc
            },
            nextInfo() {
                if (this.infoIdx < this.infographics.length - 1) {
                    this.infoIdx++
                    this.popupIdx = 0
                }
            },
            prevInfo() {
                if (this.infoIdx > 0) {
                    this.infoIdx--
                    this.popupIdx = 0
                }
            },
            closePopup() {
                this.popup = false
            }
        }"
                x-show="popup"
                x-transition
                @click.self="closePopup()"
                class="fixed inset-0 bg-black/80 z-[999] flex items-center justify-center">

                <button @click="closePopup()"
                    class="absolute top-3 right-6 bg-black/80 hover:bg-black/90
               w-9 h-9 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="white"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <button

                    @click.stop="prevInfo()"
                    class="absolute left-6 top-1/2 -translate-y-1/2 bg-black/70 hover:bg-black/90
                   p-3 rounded-full text-white z-30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="white"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button

                    @click.stop="nextInfo()"
                    class="absolute right-6 top-1/2 -translate-y-1/2 bg-black/70 hover:bg-black/90
                   p-3 rounded-full text-white z-30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="white"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="bg-white max-w-5xl w-full h-[90vh] flex overflow-hidden relative">

                    <button

                        @click.stop="prevInfo()"
                        class="absolute -left-14 top-1/2 -translate-y-1/2 bg-black/70 hover:bg-black/90
                       p-3 rounded-full text-white z-20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="white"
                            class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button

                        @click.stop="nextInfo()"
                        class="absolute -right-14 top-1/2 -translate-y-1/2 bg-black/70 hover:bg-black/90
                       p-3 rounded-full text-white z-20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="white"
                            class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="w-[480px] h-full bg-white border-r border-slate-200 overflow-hidden relative flex items-center">

                        <button
                            x-show="popupIdx > 0"
                            @click.stop="popupIdx--"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-black/80
                           p-2 rounded-full text-white z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="white"
                                class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <img :src="popupImgs[popupIdx]" class="w-full h-full object-cover">

                        <button
                            x-show="popupIdx < popupImgs.length - 1"
                            @click.stop="popupIdx++"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-black/80
                           p-2 rounded-full text-white z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="white"
                                class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                    </div>

                    <div class="flex-1 p-6 overflow-y-auto bg-white">
                        <h2 class="text-xl font-semibold mb-4" x-text="popupTitle"></h2>
                        <p class="text-sm text-gray-700 leading-relaxed" x-text="popupDesc"></p>
                    </div>

                </div>

            </div>
        </section>
    </main>

</div>
