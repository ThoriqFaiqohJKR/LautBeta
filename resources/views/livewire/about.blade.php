<div class="py-12">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="px-4 sm:px-6 lg:px-8 py-12 max-w-2xl mx-auto border rounded bg-white shadow-sm">

        <h1 class="text-2xl font-bold text-slate-800 mb-4">
            {{ $about['title'] ?? '' }}
        </h1>

        <div class="prose max-w-none text-slate-700 leading-relaxed break-words [&_*]:break-words">
            {!! $about['description'] ?? '' !!}
        </div>

    </div>
</div>