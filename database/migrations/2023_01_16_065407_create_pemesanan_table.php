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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan')->unsigned();
            $table->integer('nomor_pemesanan')->required;
            $table->string('nama_pemesan',100)->required;
            $table->string('email_pemesan',100)->required;
            $table->timestamp('tgl_pemesanan')->required;
            $table->date('tgl_check_in')->required;
            $table->date('tgl_check_out')->required;
            $table->string('nama_tamu',100)->required;
            $table->integer('jumlah_kamar')->required;
            $table->unsignedBigInteger('id_tipe_kamar');
            $table->string('status_pemesanan',100)->required;
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_tipe_kamar')->references('id_tipe_kamar')->on('tipe_kamar');
            $table->foreign('id_user')->references('id_user')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanan');
    }
};
