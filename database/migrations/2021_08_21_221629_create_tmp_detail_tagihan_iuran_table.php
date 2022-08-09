<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmpDetailTagihanIuranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_detail_tagihan_iuran', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('tmp_tagihan_iuran_id', 36);
            $table->foreign('tmp_tagihan_iuran_id')->references('id')->on('tmp_tagihan_iuran')->onDelete('cascade')->onUpdate('cascade');
            $table->string('tagihan_iuran_id', 36);
            $table->foreign('tagihan_iuran_id')->references('id')->on('tagihan_iuran')->onDelete('cascade')->onUpdate('cascade');
            $table->string('no_pembayaran');
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
        Schema::dropIfExists('tmp_detail_tagihan_iuran');
    }
}
