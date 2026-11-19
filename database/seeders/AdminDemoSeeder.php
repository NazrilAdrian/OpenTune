<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Song;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data contoh untuk demo modul Admin OpenTune.
 * Aman dijalankan berulang: menggunakan firstOrCreate dan tidak menghapus data.
 * File audio/cover hanya placeholder path agar data tampil di tabel/dashboard;
 * file audio sungguhan tidak disertakan.
 */
class AdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo: jika email sudah ada, akun tersebut tidak ditimpa.
        $admin = User::firstOrCreate(
            ['email' => 'admin@opentune.test'],
            ['username' => 'admin', 'password' => 'Admin123', 'role' => 'admin']
        );

        $demoUsers = [];
        foreach ([
            ['username' => 'nadia.demo', 'email' => 'nadia.demo@opentune.test'],
            ['username' => 'bima.music', 'email' => 'bima.music@opentune.test'],
            ['username' => 'raka.playlist', 'email' => 'raka.playlist@opentune.test'],
            ['username' => 'salsa.tunes', 'email' => 'salsa.tunes@opentune.test'],
        ] as $data) {
            $demoUsers[] = User::firstOrCreate(
                ['email' => $data['email']],
                ['username' => $data['username'], 'password' => 'User1234', 'role' => 'user']
            );
        }

        $genreDescriptions = [
            'Pop' => 'Lagu pop dengan melodi yang mudah diingat.',
            'R&B' => 'Musik dengan perpaduan rhythm, blues, dan vokal ekspresif.',
            'Indie' => 'Musik independen dengan karakter dan eksplorasi tersendiri.',
            'Rock' => 'Musik dengan dominasi gitar dan ritme yang kuat.',
            'Hip-Hop' => 'Musik dengan beat, rap, dan elemen hip-hop.',
            'Jazz' => 'Musik dengan improvisasi dan harmoni khas jazz.',
            'EDM' => 'Musik elektronik untuk suasana energik.',
            'JPop' => 'Musik pop Jepang.',
        ];

        $genres = [];
        foreach ($genreDescriptions as $name => $description) {
            $genres[$name] = Genre::firstOrCreate(['name' => $name], ['description' => $description]);
        }

        $artistData = [
            ['name' => 'Aurora Lane', 'bio' => 'Artist pop fiktif untuk kebutuhan demonstrasi OpenTune.'],
            ['name' => 'Ruang Senja', 'bio' => 'Proyek musik indie fiktif dengan nuansa santai.'],
            ['name' => 'Neon District', 'bio' => 'Grup elektronik fiktif untuk data demo.'],
            ['name' => 'Kirana', 'bio' => 'Penyanyi R&B fiktif untuk kebutuhan demonstrasi.'],
            ['name' => 'The Paper Planes', 'bio' => 'Band rock fiktif untuk data demo OpenTune.'],
            ['name' => 'Mika Haru', 'bio' => 'Artist JPop fiktif untuk data demo.'],
        ];

        $artists = [];
        foreach ($artistData as $data) {
            $artists[$data['name']] = Artist::firstOrCreate(['name' => $data['name']], ['bio' => $data['bio']]);
        }

        $albumData = [
            ['name' => 'Langit Setelah Hujan', 'artist' => 'Ruang Senja', 'date' => '2025-02-14', 'user' => $demoUsers[0]],
            ['name' => 'Midnight Signals', 'artist' => 'Neon District', 'date' => '2025-06-20', 'user' => $demoUsers[1]],
            ['name' => 'Soft Focus', 'artist' => 'Kirana', 'date' => '2024-11-08', 'user' => $demoUsers[2]],
            ['name' => 'Paper Skies', 'artist' => 'The Paper Planes', 'date' => '2025-04-03', 'user' => $demoUsers[3]],
            ['name' => 'Petal Season', 'artist' => 'Mika Haru', 'date' => '2025-08-12', 'user' => $demoUsers[0]],
        ];

        $albums = [];
        foreach ($albumData as $data) {
            $albums[$data['name']] = Album::firstOrCreate(
                ['name' => $data['name'], 'artist_id' => $artists[$data['artist']]->id],
                [
                    'user_id' => $data['user']->id,
                    'cover_path' => null,
                    'release_date' => $data['date'],
                ]
            );
        }

        $songData = [
            ['title' => 'Jendela Pagi', 'artist' => 'Ruang Senja', 'album' => 'Langit Setelah Hujan', 'genre' => 'Indie', 'user' => 0, 'duration' => 214],
            ['title' => 'Langit Setelah Hujan', 'artist' => 'Ruang Senja', 'album' => 'Langit Setelah Hujan', 'genre' => 'Pop', 'user' => 0, 'duration' => 238],
            ['title' => 'City Lights', 'artist' => 'Neon District', 'album' => 'Midnight Signals', 'genre' => 'EDM', 'user' => 1, 'duration' => 196],
            ['title' => 'Afterglow', 'artist' => 'Neon District', 'album' => 'Midnight Signals', 'genre' => 'R&B', 'user' => 1, 'duration' => 221],
            ['title' => 'Pelan-Pelan', 'artist' => 'Kirana', 'album' => 'Soft Focus', 'genre' => 'R&B', 'user' => 2, 'duration' => 205],
            ['title' => 'Blue Window', 'artist' => 'Kirana', 'album' => 'Soft Focus', 'genre' => 'Jazz', 'user' => 2, 'duration' => 247],
            ['title' => 'Runaway Notes', 'artist' => 'The Paper Planes', 'album' => 'Paper Skies', 'genre' => 'Rock', 'user' => 3, 'duration' => 189],
            ['title' => 'Paper Skies', 'artist' => 'The Paper Planes', 'album' => 'Paper Skies', 'genre' => 'Rock', 'user' => 3, 'duration' => 232],
            ['title' => 'Sakura Train', 'artist' => 'Mika Haru', 'album' => 'Petal Season', 'genre' => 'JPop', 'user' => 0, 'duration' => 201],
            ['title' => 'Neon Heart', 'artist' => 'Aurora Lane', 'album' => null, 'genre' => 'Pop', 'user' => 1, 'duration' => 218],
            ['title' => 'Weekend Replay', 'artist' => 'Aurora Lane', 'album' => null, 'genre' => 'Hip-Hop', 'user' => 2, 'duration' => 193],
        ];

        foreach ($songData as $data) {
            Song::firstOrCreate(
                ['title' => $data['title'], 'artist_id' => $artists[$data['artist']]->id],
                [
                    'user_id' => $demoUsers[$data['user']]->id,
                    'artist_id' => $artists[$data['artist']]->id,
                    'album_id' => $data['album'] ? $albums[$data['album']]->id : null,
                    'genre_id' => $genres[$data['genre']]->id,
                    // Placeholder path; file audio tidak disertakan dalam seeder.
                    'file_path' => 'demo/audio-placeholder.mp3',
                    'cover_path' => null,
                    'duration' => $data['duration'],
                ]
            );
        }

        $this->command?->info('Data demo Admin OpenTune berhasil ditambahkan tanpa menghapus data yang sudah ada.');
        $this->command?->info('Login demo: admin@opentune.test / Admin123 (jika akun baru dibuat oleh seeder).');
    }
}
