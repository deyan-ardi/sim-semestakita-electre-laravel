<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRekapanIuranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekapan_iuran', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->date('tanggal');
            $table->string('user_id', 36);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('no_tagihan')->unique();
            $table->string('no_pembayaran')->unique();
            $table->longText('deskripsi');
            $table->decimal('sub_total', 12, 2)->default(0)->nullable();
            $table->decimal('sub_total_denda', 12, 2)->default(0)->nullable();
            $table->enum('status_denda', ['DENDA', 'TIDAK DENDA'])->default('TIDAK DENDA');
            $table->decimal('total_tagihan', 12, 2);
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
        Schema::dropIfExists('rekapan_iuran');
    }
}
