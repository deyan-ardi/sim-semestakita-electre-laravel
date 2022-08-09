<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRekapanHarianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekapan_harian', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->date('tanggal');
            $table->enum('status', ['Keluar','Masuk']);
            $table->string('kode_transaksi')->unique();
            $table->decimal('total_pemasukan', 12, 2)->default(0)->nullable();
            $table->float('total_sampah');
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
        Schema::dropIfExists('rekapan_harian');
    }
}
