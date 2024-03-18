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
            $table->string('name');
            $table->string('nip');
            $table->string('no_rek')->unique();
            $table->string('nama_rekening');
            $table->unsignedBigInteger('id_golongan');
            $table->unsignedBigInteger('id_komisi');
            $table->string('jabatan');
            $table->timestamps();
            // Menambahkan kunci asing ke kolom 'id_golongan'
            $table->foreign('id_golongan')->references('id')->on('golongan')->onDelete('cascade');
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
