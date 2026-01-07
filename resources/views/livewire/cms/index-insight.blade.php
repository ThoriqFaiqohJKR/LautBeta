<div>
    {{-- Success is as dangerous as failure. --}}
    <div>

        <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-5xl mx-auto p-8">
            <div class="py-8 text-xl">
                <p>Page insight</p>
                @if (session('success'))
                <div x-data="{ show:true }"
                    x-init="setTimeout(()=>show=false,2000)"
                    x-show="show"
                    x-transition
                    class="mb-6 px-4 py-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
                @endif

            </div>
            {{-- Tabs & Tambah --}}
            <div class="flex justify-between items-center mb-6">
                <div class="flex gap-2">
                    <button wire:click="$set('type','all')" class="px-3 py-1.5 border {{ $type==='all' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700' }}">All</button>
                    <button wire:click="$set('type','feature')" class="px-3 py-1.5 border {{ $type==='feature' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700' }}">Feature</button>
                    <button wire:click="$set('type','analysis')" class="px-3 py-1.5 border {{ $type==='analysis' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700' }}">Analysis</button>
                    <button wire:click="$set('type','ngopini')" class="px-3 py-1.5 border {{ $type==='ngopini' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700' }}">Ngopini</button>
                </div>

                <a href="{{ route('cms.page.add.insight', ['locale' => app()->getLocale()]) }}">
                    <button class="px-3 py-1.5 border bg-green-600 text-white hover:bg-green-700">
                        Tambah
                    </button>
                </a>

            </div>

            {{-- Filters --}}
            <div class="flex flex-col md:flex-row md:items-end gap-3 md:gap-4 mb-6">
                <div>
                    <label class="block text-xs text-slate-500 mb-1">Publikasi</label>
                    <select wire:model.live="publikasi" class="border   px-3 py-2">
                        <option value="publish">Publish</option>
                        <option value="draf">Draf</option>
                        <option value="all">All</option>
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-xs text-slate-500 mb-1">Search</label>
                    <input
                        wire:model.live.debounce.400ms="q"
                        type="text"
                        class="w-full border   px-3 py-2"
                        placeholder="Cari judul / slug / deskripsi..." />
                </div>

                <div>
                    <label class="block text-xs text-slate-500 mb-1">Sort</label>
                    <select wire:model.live="sort" class="border   px-3 py-2">
                        <option value="latest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="az">A → Z</option>
                        <option value="za">Z → A</option>
                    </select>
                </div>
            </div>

            {{-- "Table" Desktop --}}
            <div class="hidden md:block">
                <div class="border   overflow-hidden">
                    <div class="flex px-4 py-3 bg-slate-100 text-xs font-semibold text-slate-600">

                        <div class="w-12">No</div>

                        <div class="px-52">Title</div>

                        <div class="px-12">Type</div>
                        <div class="px-14">Publikasi</div>
                        <div class="px-14">Aksi</div>

                    </div>



                    @forelse($insights as $idx => $it)
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
                            <a href="{{ route('cms.page.edit.insight', ['locale' => app()->getLocale(), 'id' => $it->id]) }}"
                                class="p-1.5 border rounded hover:bg-yellow-50 text-yellow-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.65 1.65a2.121 2.121 0 010 3l-8.49 8.49L6 18l.373-3.022 8.49-8.49a2.121 2.121 0 013 0z" />
                                </svg>
                            </a>

                            {{-- PREVIEW --}}
                            <a href="{{ route('cms.page.preview.insight', ['locale' => app()->getLocale(), 'id' => $it->id]) }}"
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

            {{-- Mobile Cards --}}
            <div class="md:hidden space-y-4">
                @forelse($insights as $idx => $it)
                <div class="border   p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-slate-500">#{{ ($page - 1) * $perPage + $idx + 1 }}</span>
                        <div class="flex gap-2">
                            <a href="{{ route('cms.page.edit.insight',  ['locale' => app()->getLocale(),'id' => $it->id]) }}" class="px-2 py-1 text-xs border  ">Edit</a>
                            <a href="{{ route('cms.page.preview.insight',  ['locale' => app()->getLocale(),'id' => $it->id]) }}" class="px-2 py-1 text-xs border  ">Preview</a>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        @if($it->image)
                        <img src="{{ Storage::url($it->image) }}" class="w-20 h-20 object-cover   border" alt="">
                        @else
                        <div class="w-20 h-20 bg-slate-100   border"></div>
                        @endif
                        <div class="min-w-0">
                            <div class="font-semibold truncate">{{ $it->title ?? '-' }}</div>
                            <div class="text-xs text-slate-500 mt-1">Publikasi: {{ $it->publikasi ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-500 py-10">Tidak ada data</div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                @include('pagination.custom', [
                'page' => $page,
                'lastPage' => $lastPage
                ])
            </div>

        </div>
    </div>


</div>
