<div class="bg-blue-100">
    {{-- The Master doesn't talk, he acts. --}}
    <div>

        <div class="w-full aspect-[3/1] bg-white flex items-center justify-center overflow-hidden mb-12">
            <img src="{{ $item['image_url'] }}"
                alt="{{ $item['title'] }}"
                class="w-full h-auto md:h-full object-contain md:object-cover md:object-top" />

        </div>

        <div class="px-4 sm:px-6 lg:max-w-3xl mx-auto py-12 items-center border bg-white">

            <h1 class="text-4xl text-center mb-4">
                {{ $item['title'] }}
            </h1>



            <div class="prose max-w-none mx-auto text-left text-gray-700 font-bold leading-snug">
                {!! $item['description'] !!}
            </div>

            <div class="prose max-w-none mx-auto text-left text-gray-700 leading-snug mt-6">
                {!! $item['content'] !!}
            </div>




        </div>
    </div>
</div>
