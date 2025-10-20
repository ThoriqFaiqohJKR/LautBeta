<div>
    {{-- Be like water. --}}
    <div>

        <div class="w-full aspect-[3/1] bg-white flex items-center justify-center overflow-hidden">
            <img src="{{ $item['image_url']  }}"
                alt="{{ $item['title'] }}"
                class="max-h-full max-w-full object-center" /> 
        </div>

        <div class="px-4 sm:px-6 lg:max-w-2xl mx-auto py-12">

            <h1 class="text-2xl sm:text-3xl md:text-4xl font-semibold text-center mb-6">
                {{ $item['title'] }}
            </h1>


            @if(!empty($item['description']))
            <div class="prose mx-auto text-justify text-gray-700 mb-8">
                {!! $item['description'] !!}
            </div>
            @endif


            <div class="prose mx-auto text-justify text-gray-700">
                {!! $item['content'] !!}
            </div>


        </div>
    </div>
</div>
