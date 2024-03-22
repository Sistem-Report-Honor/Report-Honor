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
            $table->unsignedBigInteger('id_komisi');
            $table->string('kode_unik')->unique();
            $table->date('tanggal');
            $table->time('jam');
            $table->string('qr_code');
            $table->enum('status', ['prepare','mulai', 'selesai']);
            $table->dateTime('time_expired')->nullable();

            $table->timestamps();

            $table->foreign('id_komisi')->references('id')->on('komisi')->onDelete('cascade');
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
