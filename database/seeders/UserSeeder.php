<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['Demo Colophon', 'demo@colophon.test',  'Bandung'],
            ['Raka Pratama',  'raka@colophon.test',  'Bandung'],
            ['Sari Wulandari', 'sari@colophon.test', 'Jakarta'],
            ['Dimas Aditya',  'dimas@colophon.test', 'Yogyakarta'],
            ['Nadia Putri',   'nadia@colophon.test', 'Surabaya'],
            ['Bima Saputra',  'bima@colophon.test',  'Malang'],
        ];

        foreach ($users as [$name, $email, $city]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name'      => $name,
                    'password'  => Hash::make('password'),
                    'region_id' => Region::where('name', $city)->value('id'),
                ]
            );
        }
    }
}