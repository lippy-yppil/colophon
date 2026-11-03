<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            'Fiksi'             => '#A63D2F',
            'Fantasi'           => '#5B3F8C',
            'Fiksi Ilmiah'      => '#1F5F7A',
            'Misteri'           => '#2F3E46',
            'Romantis'          => '#B5546B',
            'Sejarah'           => '#7A5C2E',
            'Sains'             => '#2E7D6B',
            'Biografi'          => '#8A6D1F',
            'Pengembangan Diri' => '#C27A2C',
            'Komik'             => '#D1495B',
        ];

        foreach ($genres as $name => $color) {
            Genre::updateOrCreate(['name' => $name], ['color' => $color]);
        }
    }
}