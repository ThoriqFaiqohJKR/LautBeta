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
    <header x-data="{ mobile:false, user:false }"
        class="sticky top-0 z-40 bg-white border-b border-slate-200">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                <div class="flex items-center gap-2 md:hidden">
                    <button @click="mobile=true"
                        class="inline-flex items-center justify-center p-2 rounded-md hover:bg-slate-100">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <a href="#" class="flex items-center gap-2">
                        <span class="inline-flex w-9 h-9 items-center justify-center rounded-lg bg-slate-900 text-white">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="m21 7.5-9-4.5-9 4.5m18 0-9 4.5m9-4.5v9l-9 4.5m0-9L3 7.5m9 4.5v9M3 7.5v9l9 4.5" />
                            </svg>
                        </span>
                        <span class="font-semibold">CMS Admin</span>
                    </a>
                </div>

                <!-- Brand + Menu Desktop -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="#" class="flex items-center gap-2">
                        <span class="inline-flex w-9 h-9 items-center justify-center rounded-lg bg-slate-900 text-white">
                            <!-- Heroicon: Cube -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="m21 7.5-9-4.5-9 4.5m18 0-9 4.5m9-4.5v9l-9 4.5m0-9L3 7.5m9 4.5v9M3 7.5v9l9 4.5" />
                            </svg>
                        </span>
                        <span class="font-semibold">CMS Admin</span>
                    </a>

                </div>
                <div class="flex items-center gap-1">
                    <a href="/cms/{{ app()->getLocale() }}/pageabout"
                        class="px-3 py-2 text-sm font-medium rounded-md text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        Home
                    </a>

                    <a href="{{ route('cms.page.about', ['locale' => app()->getLocale()]) }}"
                        class="px-3 py-2 text-sm font-medium rounded-md text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        About
                    </a>

                    <a href="{{ route('cms.page.index.insight', ['locale' => app()->getLocale()]) }}"
                        class="px-3 py-2 text-sm font-medium rounded-md text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        Insight
                    </a>

                    <a href="{{ route('cms.page.index.literacy', ['locale' => app()->getLocale()]) }}"
                        class="px-3 py-2 text-sm font-medium rounded-md text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        Literacy
                    </a>

                    <a href="{{ route('cms.page.index.agenda', ['locale' => app()->getLocale()]) }}"
                        class="px-3 py-2 text-sm font-medium rounded-md text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        Agenda
                    </a>

                    <a href="{{ route('cms.page.index.resource', ['locale' => app()->getLocale()]) }}"
                        class="px-3 py-2 text-sm font-medium rounded-md text-slate-700 hover:text-slate-900 hover:bg-slate-100">
                        Resource
                    </a>
                </div>



                <div class="flex items-center gap-2">
                    <div x-data="{ locale: window.location.pathname.includes('/id/') ? 'id' : 'en' }">
                        <template x-if="locale === 'id'">
                            <a :href="window.location.pathname.replace('/id/', '/en/')"
                                class="px-3 py-2 border rounded-md text-sm hover:bg-slate-50">EN</a>
                        </template>
                        <template x-if="locale === 'en'">
                            <a :href="window.location.pathname.replace('/en/', '/id/')"
                                class="px-3 py-2 border rounded-md text-sm hover:bg-slate-50">ID</a>
                        </template>
                    </div>


                    <div class="relative hidden md:block" @click.outside="user=false">
                        <button @click="user=!user"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-md border border-slate-200 hover:bg-slate-50">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>

                            <span class="text-sm font-medium">User</span>

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div x-show="user" x-transition
                            class="absolute right-0 mt-2 w-48 rounded-md border border-slate-200 bg-white shadow-md overflow-hidden">

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left flex items-center gap-2 px-3 py-2 text-sm hover:bg-slate-50">
                                    Logout
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Overlay -->
            <div x-show="mobile" x-transition.opacity class="fixed inset-0  bg-opacity-50 z-30" @click="mobile=false"></div>

            <!-- Menu Mobile Slide dari kiri -->
            <div class="md:hidden fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform transition-transform duration-300 z-40"
                :class="mobile ? 'translate-x-0' : '-translate-x-full'">
                <div class="pt-4 pb-3 space-y-1">
                    <a href="#" class="block px-4 py-2 rounded-md text-base text-slate-700 hover:bg-slate-100">Home</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-base text-slate-700 hover:bg-slate-100">About</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-base text-slate-700 hover:bg-slate-100">Insight</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-base text-slate-700 hover:bg-slate-100">Literacy</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-base text-slate-700 hover:bg-slate-100">Agenda</a>
                    <a href="#" class="block px-4 py-2 rounded-md text-base text-slate-700 hover:bg-slate-100">Resource</a>
                </div>

                <div class="border-t border-slate-200 pt-2 pb-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center gap-2 px-3 py-2 text-sm hover:bg-slate-50">
                            Logout
                        </button>
                    </form>


                </div>

            </div>
        </nav>
    </header>

    <!-- CONTENT -->
    <main class="flex-1">
        @yield('content')
    </main>

    @livewireScripts
</body>

</html>