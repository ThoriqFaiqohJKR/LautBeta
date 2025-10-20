<div>
    {{-- Stop trying to control. --}}
    <div>
        <div class="max-w-6xl mx-auto p-6 sm:p-10 space-y-8">
            <div class="gap-2 flex mb-4">

                <p class="text-xl ">Page Literacy </p>

            </div>
            <div class="flex justify-between items-center">

                <h2 class="text-2xl font-semibold">Literacy</h2>
                <a href="{{ route('cms.page.add.literacy', ['locale' => app()->getLocale()]) }}">
                    <button class="px-3 py-1.5 border bg-green-600 text-white">Tambah</button>
                </a>
            </div>

            {{-- Grafik --}}
            <div class="bg-white border p-6">
                <h3 class="text-xl font-semibold mb-4">
                    {{ app()->getLocale() === 'id' ? 'Grafik' : 'Graph' }}
                </h3>
                <div class="h-64 flex items-center justify-center text-slate-500 border">
                    {{ app()->getLocale() === 'id' ? 'Grafik belum tersedia (tabel masih kosong)' : 'Graph not available (table empty)' }}
                </div>
            </div>

            {{-- Jurnal --}}
            <div class="bg-white border p-6">
                <h3 class="text-xl font-semibold mb-4">
                    {{ app()->getLocale() === 'id' ? 'Jurnal' : 'Journal' }}
                </h3>

                <div class="hidden sm:grid grid-cols-5 font-semibold text-sm border-b pb-2 mb-3">
                    <div>No</div>
                    <div>{{ app()->getLocale() === 'id' ? 'Judul' : 'Title' }}</div>
                    <div>{{ app()->getLocale() === 'id' ? 'Tanggal Publikasi' : 'Publication Date' }}</div>
                    <div>Publikasi</div>
                    <div>{{ app()->getLocale() === 'id' ? 'Aksi' : 'Action' }}</div>
                </div>

                <div class="space-y-3">
                    @forelse($journals as $i => $j)
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-2 border p-3 text-sm items-center">
                        <div>#{{ $i + 1 }}</div>
                        <div>{{ $j->title ?? '-' }}</div>
                        <div>{{ $j->tanggal_publikasi ?? '-' }}</div>
                        <div>{{ ucfirst($j->publikasi ?? '-') }}</div>
                        <div>

                            <a href="{{ route('cms.page.edit.literacy', ['locale' => app()->getLocale(),'id' => $j->id]) }}" class="text-blue-600 hover:underline">
                                {{ app()->getLocale() === 'id' ? 'Edit' : 'Edit' }}
                            </a>
                            <a href="{{ $j->file_url }}" target="_blank" rel="noopener noreferrer"
                                class="text-emerald-600 hover:underline">
                                {{ $locale === 'id' ? 'Lihat' : 'Preview' }}
                            </a>


                        </div>
                    </div>
                    @empty
                    <div class="text-center text-slate-500 py-6">
                        {{ app()->getLocale() === 'id' ? 'Tidak ada jurnal' : 'No journals available' }}
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>