<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TinyUploadController extends Controller
{
    /**
     * POST /tinymce/upload-image
     * Respon: { location: "<PUBLIC_URL>" }
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120', // 5MB
        ]);

        $file = $request->file('file');

        // 1) Hash untuk dedup
        $hash = hash_file('sha256', $file->getRealPath());

        // 2) Tentukan disk yang dipakai (public utk local, default utk cloud)
        $defaultDisk = config('filesystems.default'); // contoh: 'public' atau 's3'
        $diskName    = in_array($defaultDisk, ['local', 'public']) ? 'public' : $defaultDisk;
        $disk        = Storage::disk($diskName);

        // 3) Kalau sudah ada di DB, langsung pakai URL yang tersimpan
        $existing = DB::table('images')->where([
            ['hash', '=', $hash],
            ['disk', '=', $diskName],
        ])->first();

        if ($existing) {
            return response()->json(['location' => $existing->url]);
        }

        // 4) Simpan file
        $ext      = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = $hash . '.' . $ext;                               // nama file = hash.ext
        $path     = 'uploads/tinymce/' . $filename;                 // path dalam disk

        if (! $disk->exists($path)) {
            // untuk S3, set visibility & content-type; untuk public (local) juga aman
            $disk->putFileAs('uploads/tinymce', $file, $filename, [
                'visibility'   => 'public',
                'ContentType'  => $file->getMimeType(),
                // 'CacheControl' => 'max-age=31536000, public',
            ]);
        }

        // 5) Buat URL publik
        //    - Disk 'public' (local) => /storage/...
        //    - Disk cloud (mis. s3)   => $disk->url($path)
        $url = $diskName === 'public'
            ? asset('storage/' . $path)
            : $disk->url($path);

        // 6) Simpan metadata ke DB
        DB::table('images')->insert([
            'hash'          => $hash,
            'disk'          => $diskName,
            'path'          => $path,
            'url'           => $url,
            'original_name' => $file->getClientOriginalName(),
            'mime'          => $file->getMimeType(),
            'size'          => $file->getSize(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return response()->json(['location' => $url]);
    }

    /**
     * GET /tinymce/list-images
     * Respon untuk TinyMCE image_list: [{ title: 'nama', value: 'url' }, ...]
     */
    public function list(Request $request)
    {
        $defaultDisk = config('filesystems.default');
        $diskName    = in_array($defaultDisk, ['local', 'public']) ? 'public' : $defaultDisk;

        $rows = DB::table('images')
            ->where('disk', $diskName)
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return response()->json(
            $rows->map(fn($r) => [
                'title' => $r->original_name ?: basename($r->path),
                // kembalikan ABSOLUTE URL utk dipakai <img>
                'url'   => $r->url,
                'thumb' => $r->url,   // kalau belum punya thumbnail terpisah
            ])
        );
    }
}
