<div>
    {{-- Stop trying to control. --}}
    <div>
        <div class="max-w-6xl mx-auto p-6 sm:p-10 space-y-8">
            <div class="gap-2 flex mb-4">
                <p class="text-xl">Page Resource </p>
            </div>
            <div class="flex justify-between">
                <h2 class="text-2xl font-semibold">Resources</h2>
                <a href="{{ route('cms.page.add.resource')}}"><button class="px-3 py-1.5 border bg-green-600 text-white  ">Tambah</button></a>
            </div>



            <div class="bg-white border p-6">
                <div class="flex flex-wrap items-end gap-3 mb-5">
                    <div class="">
                        <label class="block text-xs text-slate-500 mb-1">Publikasi (Report/Database)</label>
                        <select class="border rounded px-3 py-2" wire:model.live="publikasi">
                            <option value="all">All</option>
                            <option value="publish">Publish</option>
                            <option value="draf">Draf</option>
                        </select>

                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Sort (Report/Database)</label>
                        <select class="border rounded px-3 py-2" wire:model.live="sort">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="az">A → Z</option>
                            <option value="za">Z → A</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-xs text-slate-500 mb-1">Search (Report/Database)</label>
                        <input type="text" class="w-full border rounded px-3 py-2" placeholder="Cari judul / slug ..."
                            wire:model.live.debounce.400ms="q" />
                    </div>
                </div>

                <h3 class="text-xl font-semibold mb-3">Report</h3>
                <div class="hidden sm:grid grid-cols-5 font-semibold text-sm border-b pb-2 mb-3">
                    <div>No</div>
                    <div>Judul</div>
                    <div>Deskripsi</div>
                    <div>Publikasi</div>
                    <div>Aksi</div>
                </div>
                <div class="space-y-3">
                    @forelse($reports as $i => $r)
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-2 border p-3 text-sm items-center">
                        <div>#{{ $i+1 }}</div>
                        <div>{{ $r->title ?? '-' }}</div>
                        <div class="text-slate-600">{!! \Illuminate\Support\Str::limit(strip_tags($r->description ?? ''), 60) !!}</div>
                        <div>
                            {{ ucfirst($r->publikasi ?? '-') }}
                            <div class="text-xs text-slate-500">{{ $r->tanggal_publikasi ?? '-' }}</div>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('cms.page.edit.resource', ['id' => $r->id]) }}" class="text-blue-600 hover:underline">Edit</a>
                            <a href="{{ route('cms.page.preview.resource', ['id' => $r->id]) }}" class="text-blue-600 hover:underline">Preview</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-slate-500 py-6">Tidak ada report</div>
                    @endforelse
                </div>
            </div>

            <div class="border p-6">
                <h3 class="text-xl font-semibold mt-8 mb-3">Database</h3>

                <div class="flex flex-wrap items-end gap-3 mb-4">
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-xs text-slate-500 mb-1">Search Database</label>
                        <input type="text" class="w-full border rounded px-3 py-2"
                            placeholder="Cari judul / slug ..."
                            wire:model.live.debounce.400ms="databaseSearch" />
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Publikasi</label>
                        <select class="border rounded px-3 py-2" wire:model.live="databasePublikasi">
                            <option value="all">All</option>
                            <option value="publish">Publish</option>
                            <option value="draf">Draf</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Sort</label>
                        <select class="border rounded px-3 py-2" wire:model.live="databaseSort">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="az">A → Z</option>
                            <option value="za">Z → A</option>
                        </select>
                    </div>
                </div>

                <div class="hidden sm:grid grid-cols-5 font-semibold text-sm border-b pb-2 mb-3">
                    <div>No</div>
                    <div>Judul</div>
                    <div>Tanggal Publikasi</div>
                    <div>Publikasi</div>
                    <div>Aksi</div>
                </div>

                <div class="space-y-3">
                    @forelse($databases as $i => $d)
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-2 border p-3 text-sm items-center">
                        <div>#{{ $i+1 }}</div>
                        <div>{{ $d->title ?? '-' }}</div>
                        <div>{{ $d->tanggal_publikasi ?? '-' }}</div>
                        <div>{{ ucfirst($d->publikasi ?? '-') }}</div>
                        <div class="flex gap-4">
                            <a href="{{ route('cms.page.edit.resource', ['id' => $d->id]) }}" class="text-blue-600 hover:underline">Edit</a>
                            <a href="{{ route('cms.page.preview.resource', ['id' => $d->id]) }}" class="text-blue-600 hover:underline">Preview</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-slate-500 py-6">Tidak ada database</div>
                    @endforelse
                </div>
            </div>



            <div class="border  p-6">
                <h3 class="text-xl font-semibold mt-8 mb-3">Gallery</h3>

                <div class="flex flex-wrap items-end gap-3 mb-4">
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-xs text-slate-500 mb-1">Search Gallery</label>
                        <input type="text" class="w-full border rounded px-3 py-2"
                            placeholder="Cari judul / slug / type..." wire:model.live.debounce.400ms="galleryQ" />
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Publikasi</label>
                        <select class="border rounded px-3 py-2" wire:model.live="galleryPublikasi">
                            <option value="all">All</option>
                            <option value="publish">Publish</option>
                            <option value="draf">Draf</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Type</label>
                        <select class="border rounded px-3 py-2" wire:model.live="galleryType">
                            <option value="all">All</option>
                            <option value="photo">Photo</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Sort</label>
                        <select class="border rounded px-3 py-2" wire:model.live="gallerySort">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="az">A → Z</option>
                            <option value="za">Z → A</option>
                        </select>
                    </div>
                </div>

                <div class="hidden sm:grid grid-cols-6 font-semibold text-sm border-b pb-2 mb-3">
                    <div>No</div>
                    <div>Judul</div>
                    <div>Type</div>
                    <div>Tanggal Publikasi</div>
                    <div>Publikasi</div>
                    <div>Aksi</div>
                </div>
                <div class="space-y-3">
                    @forelse($galleries as $i => $g)
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-2 border p-3 text-sm items-center">
                        <div>#{{ $i+1 }}</div>
                        <div>{{ $g->title ?? '-' }}</div>
                        <div class="uppercase">{{ $g->type ?? '-' }}</div>
                        <div>{{ $g->tanggal_publikasi ?? '-' }}</div>
                        <div>{{ ucfirst($g->publikasi ?? '-') }}</div>
                        <div class="flex gap-4">
                            <a href="{{ route('cms.page.edit.resource', ['id' => $g->id]) }}" class="text-blue-600 hover:underline">Edit</a>
                            <a href="{{ route('cms.page.preview.resource', ['id' => $g->id]) }}" class="text-blue-600 hover:underline">Preview</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-slate-500 py-6">Tidak ada gallery</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>