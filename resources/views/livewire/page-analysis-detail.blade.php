<div>
    {{-- Be like water. --}}
    <div>

        <div class="w-full aspect-[3/1] bg-white flex items-center justify-center overflow-hidden">
            <img src="{{ $item['image_url'] }}"
                alt="{{ $item['title'] }}"
                class="w-full h-auto md:h-full object-contain md:object-cover md:object-top" />
        </div>

        <div class="px-4 sm:px-6 lg:max-w-2xl mx-auto py-12">

            <h1 class="text-2xl sm:text-3xl md:text-4xl  text-center mb-6">
                {{ $item['title'] }}
            </h1>


            @if(!empty($item['description']))
            <div class="prose mx-auto text-left text-gray-700 font-semibold mb-8 leading-snug">
                {!! $item['description'] !!}
            </div>
            @endif


            <div class="prose mx-auto text-left text-gray-700 leading-snug">
                {!! $item['content'] !!} <span class="inline-block w-2 h-2 bg-gray-700 rounded-full"></span>


            </div>












        </div>
    </div>
</div>
