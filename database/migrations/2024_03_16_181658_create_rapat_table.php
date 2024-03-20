<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRapatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode_unik')->unique();
            $table->date('tanggal');
            $table->time('jam');
            $table->string('qr_code');
            $table->enum('status', ['Berlangsung', 'Selesai']);
            $table->dateTime('time_expired')->nullable();

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
        Schema::dropIfExists('rapat');
    }
}
