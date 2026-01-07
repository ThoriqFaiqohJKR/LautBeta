<div x-data="{ dataSelected: @entangle('data') }" x-cloak>
    {{-- Success is as dangerous as failure. --}}
    <div>
        <div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
            <div class="py-4 text-xl mt-4">Resources</div>

            <!-- Filters (bind ke Livewire properties) -->
            <form wire:submit.prevent class="flex gap-3 flex-col md:flex-row md:items-end mb-6">
                <div>
                    <label class="block text-xs text-slate-500 mb-1">Data</label>
                    <select wire:model="data" class="border px-3 py-2">
                        <option value="all">All</option>
                        <option value="report">Report</option>
                        <option value="gallery">Gallery</option>
                    </select>
                </div>

                <div x-show="dataSelected === 'all' || dataSelected === 'gallery'" x-cloak>
                    <label class="block text-xs text-slate-500 mb-1">Type</label>
                    <select wire:model="type" class="border px-3 py-2">
                        <option value="all">All</option>
                        <option value="photo">Photo</option>
                        <option value="video">Video</option>
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-xs text-slate-500 mb-1">Search</label>
                    <div class="flex gap-2">
                        <input wire:model.debounce.500ms="q" type="text" class="w-full border px-3 py-2" placeholder="Cari... (title / filename / caption)" />
                        <button type="button" wire:click="$set('q', '')" class="px-4 py-2 bg-gray-200">Clear</button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-slate-500 mb-1">Publikasi</label>
                    <select wire:model="publikasi" class="border px-3 py-2">
                        <option value="all">All</option>
                        <option value="publish">Publish</option>
                        <option value="draf">Draf</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-slate-500 mb-1">Sort</label>
                    <select wire:model="sort" class="border px-3 py-2">
                        <option value="latest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="az">A → Z</option>
                        <option value="za">Z → A</option>
                    </select>
                </div>
            </form>

            <!-- REPORTS (server-side rendering + instant client toggle) -->
            @if($data !== 'gallery')
            <div x-show="dataSelected !== 'gallery'" x-cloak>
                <div class="text-xl py-2">Reports</div>
                <div class="hidden md:block mb-6">
                    <div class="border overflow-hidden">
                        <div class="flex px-4 py-3 bg-slate-100 text-xs font-semibold text-slate-600">

                            <div class="w-12">No</div>

                            <div class="px-52">Title</div>

                            <div class="px-12">Type</div>
                            <div class="px-14">Publikasi</div>
                            <div class="px-14">Aksi</div>

                        </div>

                        @forelse($resource as $idx => $it)
                        <div class="flex items-center px-4 py-3 border-t text-sm">
                            <div>{{ ($page - 1) * $perPage + $idx + 1 }}</div>



                            <div class="flex-1 font-medium line-clamp-2 px-12 min-h-12 flex items-center">
                                {{ $it->title ?? '-' }}
                            </div>


                            <div class="w-34 font-medium truncate">{{ $it->type ?? '-' }}</div>

                            <div class="w-32">
                                <span class="
        inline-flex px-2 py-0.5 text-xs font-medium
        {{ $it->publikasi === 'publish' ? 'bg-green-600 text-white' : '' }}
        {{ $it->publikasi === 'draf' ? 'bg-orange-500 text-white' : '' }}
    ">
                                    {{ $it->publikasi ?? '-' }}
                                </span>
                            </div>


                            <div class="w-32 flex gap-2">

                                {{-- EDIT --}}
                                <a href="{{ route('cms.page.edit.resource', ['locale' => app()->getLocale(), 'id' => $it->id]) }}"
                                    class="p-1.5 border rounded hover:bg-yellow-50 text-yellow-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.65 1.65a2.121 2.121 0 010 3l-8.49 8.49L6 18l.373-3.022 8.49-8.49a2.121 2.121 0 013 0z" />
                                    </svg>
                                </a>

                                {{-- PREVIEW --}}
                                <a href="{{ route('cms.page.preview.resource', ['locale' => app()->getLocale(), 'id' => $it->id]) }}"
                                    class="p-1.5 border rounded hover:bg-blue-50 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M1.5 12s3.75-6.75 10.5-6.75S22.5 12 22.5 12s-3.75 6.75-10.5 6.75S1.5 12 1.5 12z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>

                                {{-- DELETE --}}
                                <button wire:click="askDelete({{ $it->id }})"
                                    class="p-1.5 border rounded hover:bg-red-50 text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0115.916 21H8.084a2.25 2.25 0 01-2.244-2.327L5.772 5.79m13.456 0A48.108 48.108 0 0012 4.5c-2.35 0-4.676.164-6.228.49m13.456 0c-.04-.597-.108-1.177-.2-1.74A2.251 2.251 0 0016.916 2.25H7.084A2.25 2.25 0 004.97 3.75c-.092.563-.16 1.143-.2 1.74" />
                                    </svg>
                                </button>

                            </div>

                        </div>
                        @empty
                        <div class="px-4 py-10 text-center text-slate-500">Tidak ada data</div>
                        @endforelse
                    </div>
                </div>


                <div class="mt-8">
                    @include('pagination.custom', [
                    'page' => $page,
                    'lastPage' => $lastPage
                    ])
                </div>

            </div>
            @endif

            <!-- GALLERY (server-side rendering + instant client toggle) -->
            @if($data !== 'report')
            <div x-show="dataSelected !== 'report'" x-cloak>
                <div class="text-xl py-2">Gallery</div>
                <div class="hidden md:block mb-6">
                    <div class="border overflow-hidden">
                        <div class="flex px-4 py-3 bg-slate-100 text-xs font-semibold text-slate-600">
                            <div class="w-12">No</div>
                            <div class="px-28">Photo</div>
                            <div class="px-24">Filename</div>
                            <div class="px-20">Type</div>
                            <div class="px-20">Publikasi</div>
                            <div class="px-20">Aksi</div>
                        </div>

                        @forelse($gallery as $idx => $g)
                        <div class="flex items-center px-4 py-3 border-t text-sm">
                            <div>{{ ($page_gallery - 1) * $perPage + $idx + 1 }}</div>

                            <div class="px-10">
                                @if($g->path)
                                <img src="{{ $g->path }}" class="w-16 h-12 object-cover border rounded">
                                @else
                                <div class="w-16 h-16 bg-slate-100 border rounded"></div>
                                @endif
                            </div>

                            <div class="px-12 w-40 truncate">{{ $g->filename }}</div>

                            <div class="px-12 w-32 capitalize">{{ $g->type }}</div>

                            <div class="px-12 w-32">
                                <span class="px-2 py-0.5 text-xs font-medium {{ $g->publikasi === 'publish' ? 'bg-green-600 text-white' : 'bg-orange-500 text-white' }}">
                                    {{ $g->publikasi }}
                                </span>
                            </div>

                            <div class="w-32 flex gap-2">
                                <button wire:click.prevent="editGallery({{ $g->id }})" class="p-1.5 border rounded text-yellow-600 hover:bg-yellow-50">Edit</button>
                                <button wire:click="deleteGallery({{ $g->id }})" class="p-1.5 border rounded text-red-600 hover:bg-red-50" onclick="return confirm('Hapus galeri dan gambar terkait?')">Hapus</button>
                            </div>
                        </div>
                        @empty
                        <div class="px-4 py-10 text-center text-slate-500">Tidak ada data</div>
                        @endforelse
                    </div>
                </div>


                {{-- Pagination --}}
                <div class="mt-8">
                    @include('pagination.custom', [
                    'page' => $page,
                    'lastPage' => $lastPage
                    ])
                </div>

            </div>
            @endif
        </div>
    </div>
</div>
