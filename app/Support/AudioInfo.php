<?php

namespace App\Support;

class AudioInfo
{
    public static function duration(string $absolutePath): ?int
    {
        try {
            $info = (new \getID3)->analyze($absolutePath);
            return isset($info['playtime_seconds']) ? (int) round($info['playtime_seconds']) : null;
        } catch (\Throwable $e) {
            return null; // durasi nullable, jangan gagalkan upload
        }
    }
}