<div>
    {{-- Success is as dangerous as failure. --}}
    <div>

        <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-5xl mx-auto p-8">
            <div class="py-8 text-xl">
                <p>Page insight</p>
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
                    <div class="grid grid-cols-12 gap-3 bg-slate-100 px-4 py-3 text-xs font-semibold text-slate-600">
                        <div class="col-span-1">No</div>
                        <div class="col-span-2">Image</div>
                        <div class="col-span-5">Title EN</div>
                        <div class="col-span-2">Publikasi</div>
                        <div class="col-span-2">Aksi</div>
                    </div>

                    @forelse($insights as $idx => $it)
                    <div class="grid grid-cols-12 gap-3 items-center px-4 py-3 border-t text-sm">
                        <div class="col-span-1">{{ ($page - 1) * $perPage + $idx + 1 }}</div>

                        <div class="col-span-2">
                            @if($it->image)
                            <img src="{{ Storage::url($it->image) }}" class="w-14 h-14 object-cover   border" alt="">
                            @else
                            <div class="w-14 h-14 bg-slate-100   border"></div>
                            @endif
                        </div>

                        <div class="col-span-5 font-medium truncate">{{ $it->title ?? '-' }}</div>

                        <div class="col-span-2">
                            <span class="inline-flex px-2 py-0.5   border text-xs">{{ $it->publikasi ?? '-' }}</span>
                        </div>

                        <div class="col-span-2 flex gap-2">
                            <a href="{{ route('cms.page.edit.insight',  ['locale' => app()->getLocale(),'id' => $it->id]) }}" class="px-2.5 py-1 text-xs border   hover:bg-slate-50">Edit</a>
                            <a href="{{ route('cms.page.preview.insight', ['locale' => app()->getLocale(), 'id' => $it->id]) }}" class="px-2.5 py-1 text-xs border   hover:bg-slate-50">Preview</a>
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
                            <a href="#" class="px-2 py-1 text-xs border  ">Edit</a>
                            <a href="#" class="px-2 py-1 text-xs border  ">Preview</a>
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
            @if($lastPage > 1)
            <div class="mt-8 flex justify-center items-center gap-2">
                <button wire:click="prevPage" class="px-3 py-1 border   {{ $page==1?'opacity-50 cursor-not-allowed':'' }}">‹ Prev</button>
                <span class="px-3 py-1">Halaman {{ $page }} / {{ $lastPage }}</span>
                <button wire:click="nextPage" class="px-3 py-1 border   {{ $page==$lastPage?'opacity-50 cursor-not-allowed':'' }}">Next ›</button>
            </div>
            @endif
        </div>
    </div>


</div>
