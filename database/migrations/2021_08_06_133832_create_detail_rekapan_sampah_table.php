<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailRekapanSampahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_rekapan_sampah', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('kategori_id')->unsigned();
            // $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_kategori');
            $table->decimal('harga_kategori', 12, 2);
            $table->enum('jenis_sampah', ['organik', 'nonorganik', 'B3', 'residu']);
            $table->string('rekapan_sampah_id', 36);
            $table->foreign('rekapan_sampah_id')->references('id')->on('rekapan_sampah')->onDelete('cascade')->onUpdate('cascade');
            $table->float('jumlah_sampah');
            $table->decimal('sub_total', 12, 2);
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
        Schema::dropIfExists('detail_rekapan_sampah');
    }
}
