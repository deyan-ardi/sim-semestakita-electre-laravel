<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembayaranRutinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_rutin', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('nama_pembayaran');
            $table->longText('deskripsi');
            $table->decimal('total_biaya', 12, 2);
            $table->integer('tgl_generate');
            $table->integer('durasi_pembayaran');
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('pembayaran_rutin');
    }
}
