<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoTerbaru;

class VideoTerbaruController extends Controller
{
    // Tampilkan form (selalu edit, karena cuma 1 data)
    public function edit()
    {
        $video = VideoTerbaru::ambilAtauBuat();
        return view('video.edit', compact('video'));
    }

    // Simpan / update satu-satunya baris data
    public function update(Request $request)
    {
        $request->validate([
            'youtube_url' => 'nullable|string|max:500',
            'judul'       => 'nullable|string|max:255',
        ]);

        $video = VideoTerbaru::first();

        if ($video) {
            $video->update([
                'youtube_url' => $request->youtube_url,
                'judul'       => $request->judul,
            ]);
        } else {
            VideoTerbaru::create([
                'youtube_url' => $request->youtube_url,
                'judul'       => $request->judul,
            ]);
        }

        return redirect()->route('kelola-video.edit')->with('success', 'Video terbaru berhasil diperbarui!');
    }
}