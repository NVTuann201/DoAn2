<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeSinhThaiDichVusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('he_sinh_thai_dich_vus', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('he_sinh_thai_id')->nullable();
            $table->foreign('he_sinh_thai_id')->references('id')->on('he_sinh_thais')->onDelete('cascade');
            $table->unsignedInteger('dich_vu_he_sinh_thai_id')->nullable();
            $table->foreign('dich_vu_he_sinh_thai_id')->references('id')->on('dich_vu_he_sinh_thais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('he_sinh_thai_dich_vus');
    }
}
