<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    {{-- resources/views/livewire/cms/preview-resource.blade.php --}}
    <div class="max-w-4xl mx-auto p-6 sm:p-10">
        <div class="bg-white border rounded-2xl p-6 sm:p-10">
            @php
            $locale = app()->getLocale();
            $title = $locale === 'id' ? ($item->title_id ?? '') : ($item->title_en ?? '');
            $desc = $locale === 'id' ? ($item->description_id ?? '') : ($item->description_en ?? '');
            $content= $locale === 'id' ? ($item->content_id ?? '') : ($item->content_en ?? '');
            @endphp

            {{-- Header --}}
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide">{{ strtoupper($type ?? 'report') }}</div>
                    <h1 class="text-2xl sm:text-3xl font-semibold mt-1">{{ $title ?: '—' }}</h1>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full border text-xs">
                        {{ $item->publikasi ?? 'draf' }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full border text-xs">
                        {{ $item->status ?? 'on' }}
                    </span>
                </div>
            </div>

            {{-- Meta --}}
            <div class="text-sm text-slate-600 mb-6">
                <div>Tanggal publikasi: <span class="font-medium">{{ $item->tanggal_publikasi ?? '—' }}</span></div>
                @if(!empty($item->slug))
                <div>Slug: <span class="font-mono text-slate-700">{{ $item->slug }}</span></div>
                @endif
            </div>

            {{-- Cover image (kalau ada kolom image) --}}
            @if(!empty($item->image))
            <div class="mb-6">
                <img src="{{ Storage::url($item->image) }}" alt="cover" class="w-full max-h-[420px] object-cover rounded-lg border">
            </div>
            @endif

            {{-- Description --}}
            @if(!empty($desc))
            <div class="prose max-w-none mb-6">
                {!! $desc !!}
            </div>
            @endif

            {{-- Content (untuk report & database kamu simpan content_en/id, jadi aman ditampilkan) --}}
            @if(!empty($content))
            <div class="prose max-w-none">
                {!! $content !!}
            </div>
            @endif

            {{-- Aksi --}}
            <div class="mt-8 flex items-center gap-3">
                <a href="javascript:history.back()" class="px-4 py-2 border rounded hover:bg-slate-50">Kembali</a>
                {{-- Contoh tombol khusus per tipe, kalau nanti butuh --}}
                @if(($type ?? 'report') === 'database')
                {{-- Jika nanti ada kolom link di tabel database, tinggal ganti $item->link --}}
                {{-- <a href="{{ $item->link }}" target="_blank" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Buka Database</a> --}}
                @endif
            </div>
        </div>
    </div>

</div>