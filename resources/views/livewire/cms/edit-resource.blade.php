<div class="max-w-4xl mx-auto p-6 sm:p-10">
    <form wire:submit.prevent="save" class="bg-white border rounded-2xl p-6 sm:p-10 space-y-6">
        <div class="text-xl font-semibold">Edit Resource ({{ ucfirst($section) }})</div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Title (EN)</label>
                <input type="text" wire:model.defer="title_en" class="w-full border rounded p-2" />
                @error('title_en')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Title (ID)</label>
                <input type="text" wire:model.defer="title_id" class="w-full border rounded p-2" />
                @error('title_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea wire:model.defer="description" rows="4" class="w-full border rounded p-2"></textarea>
            @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Publikasi</label>
                <select wire:model.defer="publikasi" class="w-full border rounded p-2">
                    <option value="draf">draf</option>
                    <option value="publish">publish</option>
                </select>
                @error('publikasi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select wire:model.defer="status" class="w-full border rounded p-2">
                    <option value="on">on</option>
                    <option value="off">off</option>
                </select>
                @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Publikasi</label>
                <input type="date" wire:model.defer="tanggal_publikasi" class="w-full border rounded p-2" />
                @error('tanggal_publikasi')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        @if($section==='report')
        <div class="border-t pt-6 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">File</label>
                <input type="file" wire:model="report_file" class="w-full border rounded p-2" />
                @error('report_file')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                @if($report_path)
                <p class="text-xs text-slate-600 mt-1">Current: {{ $report_path }}</p>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">URL</label>
                <input type="url" wire:model.defer="report_url" class="w-full border rounded p-2" placeholder="https://..." />
                @error('report_url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        @endif

        @if($section==='database')
        <div class="border-t pt-6 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Link</label>
                <input type="url" wire:model.defer="db_link" class="w-full border rounded p-2" placeholder="https://..." />
                @error('db_link')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Version</label>
                <input type="text" wire:model.defer="db_version" class="w-full border rounded p-2" />
                @error('db_version')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        @endif

        @if($section==='gallery')
        <div class="border-t pt-6 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Image</label>
                <input type="file" wire:model="gallery_image" class="w-full border rounded p-2" />
                @error('gallery_image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Image URL</label>
                <input type="url" wire:model.defer="gallery_url" class="w-full border rounded p-2" placeholder="https://..." />
                @error('gallery_url')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                @if($gallery_url)
                <img src="{{ $gallery_url }}" alt="preview" class="mt-2 max-h-40 object-contain border rounded" />
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Category</label>
                <input type="text" wire:model.defer="gallery_category" class="w-full border rounded p-2" />
                @error('gallery_category')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        @endif

        <div class="pt-4 flex items-center gap-3">
            <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white">Save</button>
            <a href="{{ url()->previous() }}" class="px-4 py-2 rounded-lg border">Cancel</a>
        </div>
    </form>
</div>