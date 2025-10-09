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


    <section class="max-w-6xl mx-auto py-12  ">
      <div class="flex flex-col lg:flex-row lg:items-stretch lg:justify-between gap-8 border">



        <div class="flex-[8]">
          <h2 class="text-slate-500 text-sm font-semibold tracking-wider mb-3">INSIGHT</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($insights as $item)
            <a href="{{ $item['url'] }}" class="block group">
              <article class="relative h-full flex flex-col bg-[#bfbfbf] transition hover:scale-[1.02] duration-200">
                <div class="w-full aspect-[16/9] overflow-hidden">
                  @if(!empty($item['image']))
                  <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                  @else
                  <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <span class="text-slate-400 text-sm">No Image</span>
                  </div>
                  @endif
                </div>

                <div class="mt-4 mb-8 px-2 flex-1 flex flex-col">
                  <p class="text-[#2a5fa0] font-semibold tracking-wide text-lg sm:text-xl">
                    {{ ucfirst(strtolower($item['type'])) }}
                  </p>
                  <p class="mt-2 text-base sm:text-xl text-white leading-snug line-clamp-3">
                    {{ $item['title'] }}
                  </p>
                  <span class="absolute right-0 bottom-0 w-0 h-0 border-l-[28px] border-b-[28px] border-l-transparent border-b-gray-500"></span>
                </div>
              </article>
            </a>
            @empty
            <p class="text-slate-500">Belum ada insight terpublish.</p>
            @endforelse
          </div>
        </div>




        <div class="flex-[2]">
          <h2 class="text-slate-500 text-sm font-semibold tracking-wider mb-3">EVENT</h2>

          @if(!empty($events))
          @php($first = $events[0])
          <a href="{{ $first['url'] }}" class="block group">
            @if($first['image'])
            <img src="{{ $first['image'] }}" alt="{{ $first['title'] }}" class="w-full object-cover" />
            @else
            <div class="w-full aspect-[16/9] bg-gray-200 flex items-center justify-center">
              <span class="text-slate-400 text-sm">No Image</span>
            </div>
            @endif
            <p class="text-[#2a5fa0] font-semibold tracking-wide mt-6 text-lg">{{ $first['title'] }}</p>
          </a>

          <div class="mt-3 space-y-4">
            @foreach(array_slice($events, 1) as $e)
            <div class="border-b-2 border-slate-300 pb-3">
              <a href="{{ $e['url'] }}" class="block text-base sm:text-lg leading-snug hover:text-[#2a5fa0]">
                {{ $e['title'] }}
              </a>
              @if($e['date'])
              <p class="text-sm text-slate-500 mt-1">{{ $e['date'] }}</p>
              @endif
            </div>
            @endforeach
          </div>
          @else
          <p class="text-slate-500">Belum ada event terpublish.</p>
          @endif
        </div>

      </div>
    </section>

    <section class="bg-[#5aa0b9] py-12 sm:py-16 px-4 sm:px-12 lg:px-48 flex flex-col lg:flex-row gap-8 sm:gap-12">
      @if($ngopini)
      <div class="flex flex-col lg:flex-row my-8 sm:my-16 gap-8 lg:gap-0">
        <div class="flex-shrink-0 w-full lg:w-3/6">
          @if($ngopini['image'])
          <img src="{{ $ngopini['image'] }}" alt="{{ $ngopini['title'] }}" class="w-full h-auto rounded shadow-lg" />
          @else
          <div class="w-full aspect-[16/9] bg-gray-200 flex items-center justify-center rounded shadow-lg">
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