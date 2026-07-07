<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTerbaru extends Model
{
    protected $table = 'video_terbaru';
    protected $guarded = ['id'];

    /**
     * Ambil ID video YouTube dari berbagai format URL, lalu bentuk URL embed.
     * Mendukung: youtube.com/watch?v=, youtu.be/, youtube.com/embed/
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if (!$this->youtube_url) {
            return null;
        }

        preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $this->youtube_url,
            $matches
        );

        $videoId = $matches[1] ?? null;

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }

    // Helper: ambil satu-satunya baris data (buat baru kalau belum ada)
    public static function ambilAtauBuat(): self
    {
        return self::first() ?? new self();
    }
}