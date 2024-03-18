<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Komisi;

class KomisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menyimpan data Golongan ke dalam database
        Komisi::create([
            'komisi' => 'Akademik',
            // tambahkan field lain sesuai dengan kebutuhan
        ]);

        Komisi::create([
            'komisi' => 'Etika',
            // tambahkan field lain sesuai dengan kebutuhan
        ]);

        Komisi::create([
            'komisi' => 'Kerjasama',
            // tambahkan field lain sesuai dengan kebutuhan
        ]);

        // Menambahkan data Golongan lainnya jika diperlukan
    }
}
