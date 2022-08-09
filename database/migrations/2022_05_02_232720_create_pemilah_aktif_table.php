<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemilahAktifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemilah_aktif', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->integer('ranking');
            $table->string('periode');
            $table->string('user_id', 36);
            $table->string('hasil_electre');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('publish', ['0', '1'])->default('0');
            $table->string('alasan', 255)->nullable();
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
        Schema::dropIfExists('pemilah_aktif');
    }
}
