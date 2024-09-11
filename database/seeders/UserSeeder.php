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
            'username' => 'admin',
            'password' => bcrypt('senat_2024')
        ]);

        $admin->assignRole('admin');

        $keuangan = User::create([
            'name' => 'keuangan',
            'username' => 'keuangan',
            'password' => bcrypt('keuangan_2024')
        ]);

        $keuangan->assignRole('keuangan');
    }
}
