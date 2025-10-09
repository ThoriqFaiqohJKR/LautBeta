<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>@yield('title', 'Dashboard')</title>
  <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @vite('resources/css/app.css')
  @livewireStyles

</head>

<body class="flex flex-col min-h-screen bg-white text-slate-800 overflow-x-hidden">
  <!-- NAVBAR -->
  <nav x-data="{ mobileOpen:false, open:null, lang:'EN | ID' }">
    <div class="bg-white shadow-md fixed w-full z-30">
      <div class="lg:max-w-6xl mx-auto h-20 flex items-center justify-between">
        <!-- MOBILE BAR: Hamburger — Logo — EN | ID -->
        <div class="relative md:hidden flex items-center w-full h-[60px]">
          <button @click="mobileOpen = true" class="p-2 border absolute left-0" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <img src="{{ asset('img/Logo.png') }}" alt="Logo Auriga" class="h-[48px] w-auto mx-auto" />

          <div class="absolute right-0">
            <div class="inline-flex items-center rounded-full border p-[2px]">
              <a href="{{ url('en' . (count(request()->segments()) > 1 ? '/' . implode('/', array_slice(request()->segments(), 1)) : '')) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                class="px-2 py-1 rounded-full text-xs font-medium {{ request()->segment(1)==='en' ? 'bg-green-600 text-white' : 'text-slate-700 hover:bg-green-50' }}">EN</a>
              <a href="{{ url('id' . (count(request()->segments()) > 1 ? '/' . implode('/', array_slice(request()->segments(), 1)) : '')) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                class="px-2 py-1 rounded-full text-xs font-medium {{ request()->segment(1)==='id' ? 'bg-green-600 text-white' : 'text-slate-700 hover:bg-green-50' }}">ID</a>
            </div>
          </div>
        </div>


        <!-- DESKTOP BAR (unchanged layout) -->
        <div class="hidden md:flex items-stretch justify-between w-full h-full poppins-regular" x-data="{ desktopOpen:null }">
          <!-- Logo & Menu -->
          <div class="flex items-center justify-between h-full gap-16 w-full ">
            <!-- Logo -->
            <div class="flex items-center h-full">
              <a href="{{ '/' . app()->getLocale() }}">
                <img src="{{asset('img/Logo.png') }}" alt="Logo Auriga" class="h-14 w-auto object-contain" />
              </a>
            </div>

            <!-- Menu -->
            <div class="hidden md:flex gap-8 text-sm tracking-wide px-8">
              <!-- ABOUT dropdown -->
              <div class="relative">
                <a href="">
                  {{ __('Tentang') }}
                </a>
              </div>

              <!-- INSIGHT dropdown -->
              <div class="relative">
                <button @click="desktopOpen = (desktopOpen==='insight' ? null : 'insight')" class="hover:text-green-900 flex items-center focus:outline-none">{{ __('Wawasan') }}<template x-if="desktopOpen!=='insight'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                      <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                  </template>
                  <template x-if="desktopOpen==='insight'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                      <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd" />
                    </svg>
                  </template>
                </button>
                <div x-show="desktopOpen==='insight'" @click.outside="desktopOpen=null" class="absolute left-0 mt-2 w-40 bg-white   shadow-lg z-50">
                  <ul class="text-sm">
                    <li class="px-4 py-2 cursor-pointer"><a href="{{ route('analysis') }}">{{ __('Analisis') }}</a></li>
                    <li class="px-4 py-2 cursor-pointer"><a href="{{ route('feature') }}">{{ __('Fitur') }}</a></li>
                  </ul>
                </div>
              </div>

              <!-- LITERASI dropdown -->
              <div class="relative">
                <button @click="desktopOpen = (desktopOpen==='literasi' ? null : 'literasi')" class="hover:text-green-900 flex items-center focus:outline-none">{{ __('Literasi') }}<template x-if="desktopOpen!=='literasi'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                      <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                  </template>
                  <template x-if="desktopOpen==='literasi'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                      <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd" />
                    </svg>
                  </template>
                </button>
                <div x-show="desktopOpen==='literasi'" @click.outside="desktopOpen=null" class="absolute left-0 mt-2 w-40 bg-white   shadow-lg z-50">
                  <ul class="text-sm">
                    <li class="px-4 py-2 cursor-pointer"><a href="#">{{ __('Grafik') }}</a></li>
                    <li class="px-4 py-2 cursor-pointer"><a href="{{ route('journal') }}">{{ __('Jurnal') }}</a></li>
                    <li class="px-4 py-2 cursor-pointer"><a href="{{ route('report') }}">{{ __('Laporan') }}</a></li>
                  </ul>
                </div>
              </div>

              <!-- EVENT dropdown -->
              <div class="relative">
                <button @click="desktopOpen = (desktopOpen==='event' ? null : 'event')" class="hover:text-green-900 flex items-center focus:outline-none">{{ __('Agenda') }}<template x-if="desktopOpen!=='event'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                      <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                  </template>
                  <template x-if="desktopOpen==='event'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                      <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd" />
                    </svg>
                  </template>
                </button>
                <div x-show="desktopOpen==='event'" @click.outside="desktopOpen=null" class="absolute left-0 mt-2 w-40 bg-white   shadow-lg z-50">
                  <ul class="text-sm">
                    <li class="px-4 py-2 cursor-pointer"><a href="{{ route('event') }}">{{ __('Event') }}</a></li>
                    <li class="px-4 py-2 cursor-pointer"><a href="{{ route('activity') }}">{{ __('Aktifitas') }}</a></li>
                  </ul>
                </div>
              </div>

              <!-- GALERI dropdown -->
              <div class="relative">
                <button @click="desktopOpen = (desktopOpen==='galeri' ? null : 'galeri')" class="hover:text-green-900 flex items-center focus:outline-none">{{ __('Resources') }}<template x-if="desktopOpen!=='galeri'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                      <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                  </template>
                  <template x-if="desktopOpen==='galeri'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                      <path fill-rule="evenodd" d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z" clip-rule="evenodd" />
                    </svg>
                  </template>
                </button>
                <div x-show="desktopOpen==='galeri'" @click.outside="desktopOpen=null" class="absolute left-0 mt-2 w-40 bg-white   shadow-lg z-50">
                  <ul class="text-sm">
                    <li class="px-4 py-2 cursor-pointer"><a href="{{ route('reportresource') }}"">{{ __('Report') }}</a></li>
                    <li class=" px-4 py-2 cursor-pointer"><a href="{{ route('database') }}">{{ __('Database') }}</a></li>
                    <li class="px-4 py-2 cursor-pointer"><a href="{{ route('gallery') }}">{{ __('Galeri') }}</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Language & Search (desktop only) -->
          <div class="hidden md:flex items-center gap-8 border-l border-slate-300 pl-10 h-full">
            {{-- DESKTOP: language switch --}}
            <div x-data="{ open:false }" class="relative">
              <button @click="open = !open" class="flex items-center gap-1 text-sm cursor-pointer hover:text-green-900 focus:outline-none">
                <span class="uppercase tracking-wide">
                  {{ request()->segment(1) === 'id' ? 'INDONESIA' : 'ENGLISH' }}
                </span>
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06L10 14.59 5.23 8.27z" />
                </svg>
              </button>

              <div x-show="open" @click.outside="open=false" class="absolute left-0 mt-2 w-40 bg-white border shadow-lg z-50">
                <ul class="py-1 text-sm">
                  <li>
                    <a
                      href="{{ url('en' . (count(request()->segments()) > 1 ? '/' . implode('/', array_slice(request()->segments(), 1)) : '')) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                      class="block px-4 py-2 hover:bg-green-100 {{ request()->segment(1)==='en' ? 'font-bold text-green-900' : 'text-slate-700' }}">
                      ENGLISH
                    </a>
                  </li>
                  <li>
                    <a
                      href="{{ url('id' . (count(request()->segments()) > 1 ? '/' . implode('/', array_slice(request()->segments(), 1)) : '')) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                      class="block px-4 py-2 hover:bg-green-100 {{ request()->segment(1)==='id' ? 'font-bold text-green-900' : 'text-slate-700' }}">
                      INDONESIA
                    </a>
                  </li>
                </ul>
              </div>
            </div>


            <div class="flex items-center gap-2 text-sm cursor-pointer hover:text-green-900 h-full">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7" stroke-width="2"></circle>
                <path d="M20 20l-3.5-3.5" stroke-width="2" stroke-linecap="round"></path>
              </svg>
              <span>{{ __('Cari') }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- MOBILE DRAWER (menu + search) -->
    <div x-show="mobileOpen" x-transition class="md:hidden fixed inset-0 z-40">
      <div class="absolute inset-0 bg-black/50" @click="mobileOpen=false"></div>
      <div class="absolute left-0 top-0 h-full w-72 max-w-[85%] bg-white shadow-xl p-5 overflow-y-auto" x-data="{ openMenus: [] }">
        <div class="flex items-center justify-end mb-4">
          <button @click="mobileOpen=false" class="p-2" aria-label="Close">✕</button>
        </div>
        <!-- Search input -->
        <label class="block mb-4">
          <div class="flex items-center gap-2 border   px-3 py-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="7" stroke-width="2"></circle>
              <path d="M20 20l-3.5-3.5" stroke-width="2" stroke-linecap="round"></path>
            </svg>
            <input type="text" placeholder="Search..." class="w-full text-sm focus:outline-none" />
          </div>
        </label>
        <!-- Menu list with collapsible submenus (mobile: bisa buka beberapa sekaligus) -->
        <ul class="space-y-3">
          <li x-data="{open:false}">
            <button @click="open = !open" class="w-full text-left font-medium py-2">ABOUT</button>
            <ul x-show="open" x-transition class="pl-4 text-sm space-y-1">
              <li><a href="#" class="block py-1">{{ __('Who we are') }}</a></li>
              <li><a href="#" class="block py-1">#</a></li>
            </ul>
          </li>
          <li x-data="{open:false}">
            <button @click="open = !open" class="w-full text-left font-medium py-2">INSIGHT</button>
            <ul x-show="open" x-transition class="pl-4 text-sm space-y-1">
              <li><a href="#" class="block py-1">{{ __('Analisis') }}</a></li>
              <li><a href="#" class="block py-1">{{ __('Feature') }}</a></li>
            </ul>
          </li>
          <li x-data="{open:false}">
            <button @click="open = !open" class="w-full text-left font-medium py-2">LITERASI</button>
            <ul x-show="open" x-transition class="pl-4 text-sm space-y-1">
              <li><a href="#" class="block py-1">#</a></li>
            </ul>
          </li>
          <li x-data="{open:false}">
            <button @click="open = !open" class="w-full text-left font-medium py-2">EVENT</button>
            <ul x-show="open" x-transition class="pl-4 text-sm space-y-1">
              <li><a href="#" class="block py-1">{{ __('Event') }}</a></li>
              <li><a href="#" class="block py-1">{{ __('Activites') }}</a></li>
            </ul>
          </li>
          <li x-data="{open:false}">
            <button @click="open = !open" class="w-full text-left font-medium py-2">GALERI</button>
            <ul x-show="open" x-transition class="pl-4 text-sm space-y-1">
              <li><a href="#" class="block py-1">#</a></li>
              <li><a href="#" class="block py-1">#</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Spacer agar konten tidak tertutup navbar fixed -->
  <div class="h-20"></div>

  <!-- CONTENT -->
  <main class="flex-1">
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="border-t mt-auto bg-white poppins-regular">
    <div class="w-full flex flex-col md:flex-row items-start px-6 sm:px-12 lg:px-44 py-8 gap-8 bg-[#62a5bc]">
      <!-- Logo -->
      <div class="flex items-center h-full">
        <img src="{{asset('img/footer.png') }}" alt="Logo Auriga" class="h-20 w-auto" />
      </div>
      <!-- Text Footer -->
      <div class="text-[13px] sm:text-[14px] text-white text-left lg:w-[32rem]">
        <p>
          Auriga Nusantara Foundation is a non-governmental organization engaged in efforts to preserve natural resources and the environment to improve the quality of human life. To achieve our goals, we continue to conduct investigative research, LAUT encourage policy changes to better manage natural resources and the environment.
        </p>
      </div>
    </div>
  </footer>

  @livewireScripts
</body>

</html>