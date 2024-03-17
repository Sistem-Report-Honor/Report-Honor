<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGolonganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('golongan', function (Blueprint $table) {
            $table->id();
            $table->string('golongan');
            $table->decimal('honor', 10, 2);
            $table->decimal('pph', 5, 2);

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
        Schema::table('senat', function (Blueprint $table) {
            $table->dropForeign(['id_golongan']);
        });

        Schema::dropIfExists('golongan');
    }
}
