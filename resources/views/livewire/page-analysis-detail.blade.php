<div>
    {{-- Be like water. --}}
    <div>

        <div class="w-full aspect-[3/1] bg-white flex items-center justify-center overflow-hidden">
            <img src="{{ $item['image_url'] }}"
                alt="{{ $item['title'] }}"
                class="w-full h-auto md:h-full object-contain md:object-cover md:object-top" />
        </div>

        <div class="px-4 sm:px-6 lg:max-w-3xl mx-auto py-12">

            <div class="text-2xl sm:text-3xl md:text-4xl text-center mb-6 max-w-none">
                {{ $item['title'] }}
            </div>


            <div class="prose max-w-none text-left text-gray-700 font-semibold mb-8 leading-snug">
                {!! $item['description'] !!}
            </div>


            <div class="prose max-w-none text-left text-gray-700 leading-relaxed">
                {!! $item['content'] !!}
            </div>
        </div>
    </div>
</div>
