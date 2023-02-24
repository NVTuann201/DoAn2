<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThanhPhanDoanThanhTrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thanh_phan_doan_thanh_tras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten')->nullable();
            $table->string('chuc_vu')->nullable();
            $table->string('don_vi_cong_tac')->nullable();

            $table->unsignedInteger('ket_qua_thanh_tra_tinh_id');
            $table->foreign('ket_qua_thanh_tra_tinh_id')->references('id')->on('ket_qua_thanh_tra_tinhs')->onDelete('cascade');

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
        Schema::dropIfExists('thanh_phan_doan_thanh_tras');
    }
}
