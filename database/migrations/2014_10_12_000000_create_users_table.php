<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('foto')->nullable();
            $table->bigInteger('no_telp')->unique()->nullable();
            $table->string('no_member')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->longText('alamat')->nullable();
            $table->string('pembayaran_rutin_id', 36)->nullable();
            $table->foreign('pembayaran_rutin_id')->references('id')->on('pembayaran_rutin')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // For WhatsApp Login
            $table->string('password_whatsapp')->default(Hash::make('12345678'));
            $table->integer('role');
            // For Iuran
            $table->boolean('status_iuran')->default(false);
            // For Change Email
            $table->string('re_email')->nullable();
            $table->string('re_token')->nullable();
            $table->string('re_expired')->nullable();
            // End Change Email
            // For Mobile App
            $table->string('is_mobile')->default('0');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
