<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSenatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senat', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->string('no_rek')->unique();
            $table->string('nama_rekening');
            $table->unsignedBigInteger('id_golongan');
            $table->string('jabatan');

            // Menambahkan kunci asing ke kolom 'id' pada tabel 'users'
            $table->foreign('id')->references('id_senat')->on('users')->onDelete('cascade');

            // Menambahkan kunci asing ke kolom 'id_golongan' pada tabel 'golongan'
            $table->foreign('id_golongan')->references('id')->on('golongan')->onDelete('cascade');

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
        Schema::dropIfExists('senat');
    }
}
