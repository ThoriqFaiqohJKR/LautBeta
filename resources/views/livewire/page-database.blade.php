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

                <ul class="py-1 space-y-1">
                    @foreach($entries as $it)
                    <li class="flex gap-2 py-1 text-sm leading-relaxed">
                        <span class="font-medium w-48 flex-shrink-0 text-slate-800">{{ $it->title }}</span>
                        <span class="text-slate-500">:</span>
                        <span class="text-slate-600 break-words flex-1
               [&_p]:inline [&_p]:m-0
               [&_ul]:inline [&_ul]:m-0 [&_li]:inline [&_li]:m-0">
                            {!! $it->desc !!}
                        </span>
                    </li>


                    @endforeach
                </ul>
            </section>
            @endforeach
        </div>
    </div>


</div>
