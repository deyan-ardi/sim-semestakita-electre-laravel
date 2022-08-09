<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPengangkutanPenilaianHarianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pengangkutan_penilaian_harian', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('pengangkutan_penilaian_harian_id', 36);
            $table->foreign('pengangkutan_penilaian_harian_id', 'detail_penilaian_harian_id_foreign')->references('id')->on('pengangkutan_penilaian_harian')->onDelete('cascade')->onUpdate('cascade');
            $table->string('kriteria_id', 36);
            $table->foreign('kriteria_id')->references('id')->on('kriteria')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('nilai_kriteria', ['iya', 'tidak']);
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
        Schema::dropIfExists('detail_pengangkutan_penilaian_harian');
    }
}
