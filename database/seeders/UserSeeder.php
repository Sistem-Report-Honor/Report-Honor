<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $admin->assignRole('admin');
        
        $pimpinan = User::create([
            'name' => 'pimpinan',
            'email' => 'pimpinan@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $pimpinan->assignRole('pimpinan');

        $keuangan = User::create([
            'name' => 'keuangan',
            'email' => 'keuangan@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $keuangan->assignRole('keuangan');

        $anggota = User::create([
            'name' => 'anggota',
            'email' => 'anggota@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $anggota->assignRole('anggota');
    }
}
