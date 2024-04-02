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
            'golongan' => 'Golongan III',
            'honor' => 223684.00,
            'pph' => 11184.00,
            // tambahkan field lain sesuai dengan kebutuhan
        ]);

        Golongan::create([
            'golongan' => 'Golongan IV',
            'honor' => 250000.00,
            'pph' => 37500.00,
            // tambahkan field lain sesuai dengan kebutuhan
        ]);

        // Menambahkan data Golongan lainnya jika diperlukan
    }
}
