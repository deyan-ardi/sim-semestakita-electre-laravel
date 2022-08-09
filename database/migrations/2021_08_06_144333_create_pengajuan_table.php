<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengajuanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->date('tanggal');
            $table->string('nama_pelanggan');
            $table->string('alamat_pelanggan');
            $table->bigInteger('kontak_pelanggan');
            $table->longText('lokasi_ambil');
            $table->float('jarak');
            $table->decimal('biaya', 12, 2);
            $table->enum('status', ['lunas','pending']);
            $table->string('user_id', 36);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('pengajuan');
    }
}
