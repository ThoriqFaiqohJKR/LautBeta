<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div class="max-w-5xl mx-auto p-4 space-y-4">
        <h2 class="text-xl font-semibold">Glossary</h2>

        <div class="divide-y">
            @foreach($groups as $ltr => $entries)
            <section>
                <div class="sticky top-0 bg-white/80 backdrop-blur px-1 py-2 font-semibold text-slate-700">
                    {{ $ltr }}
                </div>

                <ul class="py-1 space-y-2">
                    @foreach($entries as $it)
                    <li class="flex gap-3">
                        <div class="mt-1 h-2 w-2 rounded-full bg-slate-300 flex-shrink-0"></div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-baseline gap-x-2">
                                <span class="font-medium">{{ $it->title }}</span>
                            </div>
                            @if(!empty($it->desc))
                            <p class="text-sm text-slate-600 leading-relaxed line-clamp-2">{!! $it->desc !!}</p>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </section>
            @endforeach
        </div>
    </div>

</div>