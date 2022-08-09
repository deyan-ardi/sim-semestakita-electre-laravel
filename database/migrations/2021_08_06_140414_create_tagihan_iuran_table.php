<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagihanIuranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihan_iuran', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('no_tagihan')->unique();
            $table->string('user_id', 36);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal');
            $table->longText('deskripsi');
            $table->date('due_date');
            $table->enum('status', ['PAID','UNPAID','OVERDUE']);
            $table->decimal('sub_total', 12, 2);
            $table->decimal('sub_total_denda', 12, 2);
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
        Schema::dropIfExists('tagihan_iuran');
    }
}
