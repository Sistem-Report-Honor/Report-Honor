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
        
        $keuangan = User::create([
            'name' => 'keuangan',
            'email' => 'keuangan@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $keuangan->assignRole('keuangan');

    }
}
