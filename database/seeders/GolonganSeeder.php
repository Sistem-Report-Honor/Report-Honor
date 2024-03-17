<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Golongan;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menyimpan data Golongan ke dalam database
        Golongan::create([
            'golongan' => 'Golongan I',
            'honor' => 5000000.00,
            'pph' => 5.00,
            // tambahkan field lain sesuai dengan kebutuhan
        ]);

        Golongan::create([
            'golongan' => 'Golongan II',
            'honor' => 4000000.00,
            'pph' => 4.00,
            // tambahkan field lain sesuai dengan kebutuhan
        ]);

        Golongan::create([
            'golongan' => 'Golongan III',
            'honor' => 3000000.00,
            'pph' => 3.00,
            // tambahkan field lain sesuai dengan kebutuhan
        ]);

        // Menambahkan data Golongan lainnya jika diperlukan
    }
}
