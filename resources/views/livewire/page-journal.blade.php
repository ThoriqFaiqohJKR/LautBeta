<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="max-w-6xl mx-auto p-10 space-y-4 border ">
        <h2 class="text-xl font-semibold mb-4">Journal List</h2>

        @forelse($journals as $j)
        <div class="flex gap-4 items-start border-b pb-3">
            {{-- LEFT: cover style --}}
            <div class="flex-shrink-0 flex flex-col items-center">
                <div class="aspect-[2/3] w-16 sm:w-20 overflow-hidden   border bg-slate-100">
                    @if(!empty($j['image_url']))
                    <img src="{{ $j['image_url'] }}"
                        alt="{{ $j['title'] ?? 'Journal' }}"
                        class="w-full h-full object-contain scale-95 hover:scale-100 transition duration-200">
                    @else
                    <div class="w-full h-full grid place-items-center text-[10px] text-slate-400">No Image</div>
                    @endif
                </div>

                <div class="flex gap-1 mt-1">
                    @if(!empty($j['file_url']))
                    <a href="{{ $j['file_url'] }}" target="_blank"
                        class="text-[9px] px-1 py-0.5 border   hover:bg-slate-50">preview</a>
                    <a href="{{ $j['file_url'] }}" download
                        class="text-[9px] px-1 py-0.5 border   hover:bg-slate-50">Download</a>
                    @endif
                </div>
            </div>



            {{-- RIGHT: title + desc --}}
            <div class="flex-1 px-4">
                <h3 class="text-base font-semibold">{{ $j['title'] ?? 'Untitled' }}</h3>
                @if(!empty($j['tanggal_publikasi']))
                <p class="text-xs text-slate-500 mb-1">
                    {{ \Illuminate\Support\Carbon::parse($j['tanggal_publikasi'])->toFormattedDateString() }}
                </p>
                @endif
                <p class="text-sm text-slate-600 line-clamp-3">{!! $j['description'] !!}</p>
            </div>
        </div>
        @empty
        <p class="text-slate-500">No journals found.</p>
        @endforelse
    </div>


</div>