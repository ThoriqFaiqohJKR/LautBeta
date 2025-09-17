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

    <!-- Insight + Event Section -->
    <section class="px-4 sm:px-8 lg:px-52 py-12 sm:py-16">
      <div class="grid grid-cols-1 lg:grid-cols-[4fr_1fr] gap-8 items-start">
        <!-- KIRI: INSIGHT -->
        <div class="pr-0 lg:pr-10">
          <h2 class="text-slate-500 text-sm font-semibold tracking-wider mb-3">
            INSIGHT
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Card 1 -->
            <article class="relative h-full flex flex-col bg-[#bfbfbf]">
              <img
                src="{{ asset('img/berita 1.png') }}"
                alt="Insight 1"
                class="object-cover w-full" />
              <div class="p-6 sm:p-8">
                <p class="text-[#2a5fa0] font-semibold tracking-wide text-lg sm:text-xl">
                  INSIGHT
                </p>
                <h3 class="mt-2 text-base sm:text-xl text-white leading-snug">
                  Cegah Pantura Jawa Tenggelam, Pemerintah Susun Peta Jalan
                  “Giant Sea Wall”
                </h3>
                <!-- Lipatan sudut -->
                <span
                  class="absolute right-0 bottom-0 w-0 h-0 border-l-[28px] border-b-[28px] border-l-gray-500 border-b-transparent"></span>
              </div>
            </article>

            <!-- Card 2 -->
            <article class="relative h-full flex flex-col bg-[#bfbfbf]">
              <img
                src="{{asset('img/berita 1.png') }}"
                alt="Insight 1"
                class="object-cover w-full" />
              <div class="p-6 sm:p-8">
                <p class="text-[#2a5fa0] font-semibold tracking-wide text-lg sm:text-xl">
                  INSIGHT
                </p>
                <h3 class="mt-2 text-base sm:text-xl text-white leading-snug">
                  Cegah Pantura Jawa Tenggelam, Pemerintah Susun Peta Jalan
                  “Giant Sea Wall”
                </h3>
                <!-- Lipatan sudut -->
                <span
                  class="absolute right-0 bottom-0 w-0 h-0 border-l-[28px] border-b-[28px] border-l-gray-500 border-b-transparent"></span>
              </div>
            </article>
          </div>
        </div>

        <!-- KANAN: EVENT -->
        <aside class="max-w-full lg:max-w-[220px]">
          <h2 class="text-slate-500 text-sm font-semibold tracking-wider mb-3">
            EVENT
          </h2>
          <img
            src="{{ asset('img/berita 1.png') }}"
            alt="Event Thumb"
            class="w-full h-32 object-cover" />
          <p class="text-[#2a5fa0] font-semibold tracking-wide mt-6 text-sm">
            NAMA EVENT
          </p>
          <div class="mt-3 space-y-4">
            <div class="border-b-2 border-slate-300 pb-3">
              <p class="text-base sm:text-lg leading-snug">
                Tema event Tema event Tema event
              </p>
            </div>
            <div class="border-b-2 border-slate-300 pb-3">
              <p class="text-base sm:text-lg leading-snug">
                Event sebelumnya Event sebelumnya
              </p>
            </div>
            <div class="border-b-2 border-slate-300 pb-3">
              <p class="text-base sm:text-lg leading-snug">
                Event sebelumnya Event sebelumnya
              </p>
            </div>
          </div>
        </aside>
      </div>
    </section>

    <section class="bg-[#5aa0b9] py-12 sm:py-16 px-4 sm:px-12 lg:px-48 flex flex-col lg:flex-row gap-8 sm:gap-12">
      <div class="flex flex-col lg:flex-row my-8 sm:my-16 gap-8 lg:gap-0">
        <div class="flex-shrink-0 w-full lg:w-3/6">
          <img
            src="{{ asset('img/ngopini.png') }}"
            alt="Ngopini"
            class="w-full h-auto rounded shadow-lg" />
        </div>
        <div class="text-white lg:w-[38rem] px-0 sm:px-8 lg:px-24">
          <p class="uppercase tracking-widest text-sm mb-2">NGOPINI</p>

          <h3 class="text-2xl sm:text-3xl font-semibold mb-4">
            Cegah Pantura Jawa Tenggelam, Pemerintah Susun Peta Jalan “Giant
            Sea Wall”
          </h3>
          <div class="lg:w-[20rem]">
            <p class="text-sm mb-6">
              <span class="font-semibold">JULI 2025</span> | Xerundistius
              ipsum nulpa destibus de omnis voluptis, si dipitempore, quiaerum
              quibus. Harciatur aborit fugiatusdae num reptatur
            </p>
            <a
              href="#"
              class="inline-flex items-center gap-2 text-sm font-semibold">VIEW <span>&rarr;</span></a>
          </div>
        </div>
      </div>
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