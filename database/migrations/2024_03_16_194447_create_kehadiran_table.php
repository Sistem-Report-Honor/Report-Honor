<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKehadiranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rapat');
            $table->unsignedBigInteger('id_senat');
            $table->dateTime('waktu');
            $table->enum('verifikasi', ['Hadir', 'Tidak Hadir']);

            // Menambahkan kunci asing ke kolom 'id_rapat' pada tabel 'rapat'
            $table->foreign('id_rapat')->references('id')->on('rapat')->onDelete('cascade');

            // Menambahkan kunci asing ke kolom 'id_senat' pada tabel 'senat'
            $table->foreign('id_senat')->references('id')->on('senat')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kehadiran');
    }
}
