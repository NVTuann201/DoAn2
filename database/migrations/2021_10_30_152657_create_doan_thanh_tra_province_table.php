<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoanThanhTraProvinceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doan_thanh_tra_province', function (Blueprint $table) {
            $table->unsignedInteger('doan_thanh_tra_id');
            $table->foreign('doan_thanh_tra_id')->references('id')->on('doan_thanh_tras')->onDelete('cascade');
            $table->unsignedInteger('province_id');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doan_thanh_tra_province');
    }
}
