<div>
    <div class="max-w-4xl mx-auto p-6 sm:p-0 space-y-6">
        <div class="py-4">
            <h2 class="text-xl font-semibold">Journal List</h2>

            @forelse($journals as $j)
            <div class="flex gap-4 items-start border-b pb-4 py-4">
                <div class="flex-shrink-0 flex flex-col items-center">
                    <div class="aspect-[2/3] w-24 overflow-hidden border bg-slate-100">
                        @if(!empty($j['image_url']))
                        <img src="{{ $j['image_url'] }}"
                            alt="{{ $j['title'] ?? 'Journal' }}"
                            class="w-full h-full object-contain hover:scale-100 transition duration-200">
                        @else
                        <div class="w-full h-full grid place-items-center text-sm text-slate-400">
                            No Image
                        </div>
                        @endif
                    </div>


                    @if(!empty($j['file_url']))
                    <div class="flex gap-1 mt-1">
                        <a href="{{ route('previewjournal', ['locale' => app()->getLocale(), 'id' => $j['id']]) }}"
                            target="_blank"
                            class="text-[10px] px-1.5 py-0.5 border rounded hover:bg-slate-50">
                            Preview
                        </a>

                        <a href="{{ $j['file_url'] }}" download
                            class="text-[10px] px-1.5 py-0.5 border rounded hover:bg-slate-50">
                            Download
                        </a>
                    </div>
                    @endif
                </div>

                <div class="flex-1 px-2 sm:px-4">
                    <h3 class="text-base font-semibold leading-snug">{{ $j['title'] ?? 'Untitled' }}</h3>
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
</div>
