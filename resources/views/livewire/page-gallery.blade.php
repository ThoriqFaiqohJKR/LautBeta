<div class="px-4 sm:px-6 lg:px-8 py-8 max-w-7xl mx-auto space-y-12">
    @foreach($items as $category => $data)
    <section class="space-y-6">
        <h2 class="text-lg font-semibold text-slate-800">{{ $category }}</h2>

        {{-- PHOTO --}}
        @if(!empty($data['photo']))
        <h3 class="text-sm font-medium text-slate-500 mb-2">Photo</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($data['photo'] as $p)
            @php $pid = 'photo-'.$p['id']; @endphp
            <input id="{{ $pid }}" type="checkbox" class="peer sr-only">

            <label for="{{ $pid }}" class="block relative cursor-pointer">
                <img src="{{ $p['photo'] }}" alt="{{ $p['title'] }}" class="w-full h-40 object-cover rounded-lg">
            </label>

            {{-- Modal --}}
            <label for="{{ $pid }}"
                class="fixed inset-0 z-[100] bg-black/70 opacity-0 pointer-events-none
         peer-checked:opacity-100 peer-checked:pointer-events-auto transition">
                <div class="absolute inset-0 flex items-center justify-center p-4 sm:p-6">
                    <div class="relative w-full max-w-2xl bg-white p-4 sm:p-6 rounded-2xl shadow-xl">
                        {{-- Tombol close --}}
                        <label for="{{ $pid }}"
                            class="absolute -top-10 right-0 sm:-top-12 sm:-right-2 bg-white/90 hover:bg-white
               rounded-full px-3 py-1 text-sm cursor-pointer shadow">✕</label>

                        {{-- Frame 16:9 --}}
                        <div class="aspect-[16/9] w-full overflow-hidden rounded-xl bg-black/5">
                            <img
                                src="https://drive.google.com/thumbnail?id=1HXT2-JktE3mQm8myfcFifrXpI5E5M3iW"
                                alt="{{ $p['title'] }}"
                                class="w-full h-full object-cover"
                                loading="eager"
                                decoding="async">
                        </div>

                        {{-- Judul dan deskripsi --}}
                        <div class="mt-4 text-center">
                            @if(!empty($p['title']))
                            <h3 class="text-base sm:text-lg font-semibold text-slate-800">{{ $p['title'] }}</h3>
                            @endif
                            @if(!empty($p['desc']))
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed max-w-prose mx-auto">
                                {{ $p['desc'] }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </label>


            @endforeach
        </div>
        @endif

        {{-- VIDEO --}}
        @if(!empty($data['video']))
        <h3 class="text-sm font-medium text-slate-500 mt-8 mb-2">Video</h3>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($data['video'] as $v)
            @php $vid = 'video-'.$v['id']; @endphp
            <input id="{{ $vid }}" type="checkbox" class="peer sr-only">

            <label for="{{ $vid }}" class="block relative cursor-pointer">
                {!! str_replace(
                ['width="560"', 'height="315"'],
                ['class="w-full h-40 rounded-lg pointer-events-none"', ''],
                $v['photo']
                ) !!}
                <span class="absolute inset-0 grid place-items-center bg-black/0 hover:bg-black/30 rounded-lg transition">
                    <span class="text-white text-xl font-bold"></span>
                </span>
            </label>

            <label for="{{ $vid }}"
                class="fixed inset-0 z-[100] bg-black/80 opacity-0 pointer-events-none
                       peer-checked:opacity-100 peer-checked:pointer-events-auto transition">
                <div class="absolute inset-0 flex items-center justify-center p-2 sm:p-6">
                    <div class="relative w-full max-w-5xl">
                        <label for="{{ $vid }}"
                            class="absolute -top-10 right-0 bg-white/90 hover:bg-white rounded-full
                                   px-3 py-1 text-sm cursor-pointer shadow">✕</label>
                        <div class="aspect-video bg-black rounded-lg overflow-hidden shadow-lg">
                            {!! str_replace(
                            ['width="560"', 'height="315"'],
                            ['class="w-full h-full"', ''],
                            $v['photo']
                            ) !!}
                        </div>
                    </div>
                </div>
            </label>
            @endforeach
        </div>
        @endif

    </section>
    @endforeach
</div>
</div>