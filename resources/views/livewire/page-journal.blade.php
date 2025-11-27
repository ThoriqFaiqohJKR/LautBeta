<div>
    <div class="max-w-6xl mx-auto p-4 sm:p-2 space-y-6 py-2 sm:py-8">
        <div class="py-4">
            <h2 class="text-xl font-semibold">Journal List</h2>

            @forelse($journals as $j)
            <div
                class="flex flex-col md:flex-row gap-6 py-6 border-b border-gray-200 max-w-4xl">
                <!-- Gambar -->
                <div
                    class="w-40 aspect-[1/1.414] overflow-hidden bg-gray-200 flex-shrink-0">
                    <img
                        src="{{ $j['image_url'] }}"
                        alt="{{ $j['title'] ?? 'Journal' }}"
                        class="w-full h-full object-cover" />
                </div>

                <!-- Teks + Tombol -->
                <div class="flex flex-col flex-1 leading-snug max-w-xl">
                    <!-- Judul + Paragraf -->
                    <div>
                        <p class="text-xl font-semibold">
                            {{ $j['title'] ?? 'Untitled' }}
                        </p>
                        <p class="mt-1">
                            {!! $j['description'] !!}
                        </p>
                    </div>

                    <!-- Tombol selalu di bawah -->
                    <div class="flex gap-3 mt-auto pt-4">
                        <a
                            href="#"
                            class="px-3 py-1.5 bg-blue-600 text-white text-sm">Download</a>
                        <a
                            href="#"
                            class="px-3 py-1.5 bg-gray-600 text-white text-sm">Preview</a>
                    </div>
                </div>
            </div>

            @empty
            <p class="text-slate-500">No journals found.</p>
            @endforelse
        </div>
    </div>
</div>
