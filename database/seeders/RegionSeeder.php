<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['Jakarta', 'DKI Jakarta'], ['Bandung', 'Jawa Barat'], ['Bogor', 'Jawa Barat'],
            ['Surabaya', 'Jawa Timur'], ['Malang', 'Jawa Timur'], ['Semarang', 'Jawa Tengah'],
            ['Yogyakarta', 'DI Yogyakarta'], ['Medan', 'Sumatera Utara'],
            ['Palembang', 'Sumatera Selatan'], ['Makassar', 'Sulawesi Selatan'],
            ['Denpasar', 'Bali'], ['Balikpapan', 'Kalimantan Timur'],
        ];

        foreach ($cities as [$name, $province]) {
            Region::firstOrCreate(['name' => $name], ['province' => $province]);
        }
    }
}
