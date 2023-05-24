<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id('id_kamar')->unsigned();
            $table->integer('nomor')->required;
            $table->unsignedBigInteger('id_tipe_kamar');
            $table->timestamps();

            $table->foreign('id_tipe_kamar')->references('id_tipe_kamar')->on('tipe_kamar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamar');
    }
};
