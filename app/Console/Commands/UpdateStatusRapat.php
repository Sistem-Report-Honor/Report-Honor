<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Rapat;
use Illuminate\Console\Command;

class UpdateStatusRapat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateStatusRapat';
    protected $description = 'Update Status Rapat Berdasarkan Waktu Kadaluarsanya';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Ambil semua rapat yang masih aktif
        $rapats = Rapat::where('status', 'mulai')->get();

        foreach ($rapats as $rapat) {
            // Periksa apakah waktu kedaluwarsa telah lewat
            if (Carbon::now() > $rapat->expiration_time) {
                // Ubah status rapat menjadi "selesai"
                $rapat->status = 'selesai';
                $rapat->save();
            }
        }

        $this->info('Meeting status updated successfully.');
    }
}
