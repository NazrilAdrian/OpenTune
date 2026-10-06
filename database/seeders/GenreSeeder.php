<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['JPop','Pop','Rock','Hip-Hop','R&B','Jazz','Classical','EDM','Indie','Metal'] as $name) {
            \App\Models\Genre::firstOrCreate(['name' => $name]);
        }
    }
}
