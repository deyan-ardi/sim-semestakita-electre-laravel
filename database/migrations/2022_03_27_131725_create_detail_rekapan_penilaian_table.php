<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailRekapanPenilaianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_rekapan_penilaian', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('kriteria_id', 36);
            $table->foreign('kriteria_id')->references('id')->on('kriteria')->onDelete('cascade')->onUpdate('cascade');
            $table->string('rekapan_penilaian_id', 36);
            $table->foreign('rekapan_penilaian_id')->references('id')->on('rekapan_penilaian')->onDelete('cascade')->onUpdate('cascade');
            $table->float('total_nilai');
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
        Schema::dropIfExists('detail_rekapan_penilaian');
    }
}
