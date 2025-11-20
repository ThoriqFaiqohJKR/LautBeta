<div class="py-12">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="px-4 sm:px-6 lg:px-8 py-2 max-w-2xl mx-auto  rounded bg-white ">

        <div class="relative w-full overflow-hidden shadow-lg">
            <img
                src="{{ asset('img/laut.jpg') }}"
                class="w-full h-auto md:h-full object-contain md:object-cover md:object-top"
                alt="Index Image" />
        </div>

        <h1 class="text-2xl font-bold text-slate-800 mt-8 mb-4">
            {{ $about['title'] ?? '' }}
        </h1>

        <div class="prose max-w-none text-slate-700 leading-relaxed break-words [&_*]:break-words">
            {!! $about['description'] ?? '' !!}
        </div>

    </div>
</div>
