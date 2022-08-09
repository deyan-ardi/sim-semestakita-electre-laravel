<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailRekapanHarianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_rekapan_harian', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('kategori_id')->unsigned();
            // $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_kategori');
            $table->decimal('harga_kategori', 12, 2);
            $table->string('rekapan_harian_id', 36);
            $table->enum('jenis_sampah', ['organik', 'nonorganik', 'B3', 'residu']);
            $table->foreign('rekapan_harian_id')->references('id')->on('rekapan_harian')->onDelete('cascade')->onUpdate('cascade');
            $table->float('jumlah_sampah');
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
        Schema::dropIfExists('detail_rekapan_harian');
    }
}
