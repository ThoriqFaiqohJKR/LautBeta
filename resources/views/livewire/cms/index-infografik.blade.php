<div>
    <div class="max-w-5xl mx-auto p-6 bg-white shadow rounded-lg space-y-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-slate-700">Infografik</h2>

            <a href="{{ route('cms.page.add.infografik', app()->getLocale()) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Tambah Baru
            </a>
        </div>

        {{-- LIST --}}
        <div class="divide-y">
            @foreach($items as $item)
            <div class="py-4 flex items-start gap-4">

                <!-- GAMBAR PERTAMA -->
                <div class="w-36 aspect-[4/5] bg-gray-200 overflow-hidden">
                    @if($item->first_image)
                    <img src="{{ $item->first_image }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-500">
                        No Image
                    </div>
                    @endif
                </div>

                <div class="flex-1">
                    <h3 class="text-lg font-semibold">{{ $item->title_id }}</h3>

                    <p class="text-sm text-gray-500">
                        Status: <span class="font-medium">{{ $item->publikasi }}</span>
                    </p>

                    <div class="mt-2 flex gap-3">
                        <a href="/cms/infografik/edit/{{ $item->id }}"
                            class="text-blue-600 hover:underline">
                            Edit
                        </a>

                        <button
                            wire:click="openDelete({{ $item->id }})"
                            class="text-red-600 hover:underline">
                            Hapus
                        </button>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- MODAL LIVEWIRE --}}
        @if($confirmingDelete)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
                <h2 class="text-lg font-semibold mb-3">Yakin ingin menghapus?</h2>

                <p class="text-sm text-gray-600 mb-4">
                    Infografik yang dihapus tidak dapat dikembalikan.
                </p>

                <div class="flex justify-end gap-3">
                    <button
                        wire:click="$set('confirmingDelete', false)"
                        class="px-4 py-2 bg-gray-300 rounded">
                        Batal
                    </button>

                    <button
                        wire:click="delete"
                        class="px-4 py-2 bg-red-600 text-white rounded">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>