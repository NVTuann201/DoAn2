<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOTieuChuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_tieu_chuans', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->string('khu_sinh_thai')->nullable();
            $table->string('kich_thuoc')->nullable();
            $table->string('hinh_dang')->nullable();
            $table->unsignedInteger('dia_diem_id')->nullable();
            $table->foreign('dia_diem_id')->references('id')->on('protected_areas');
            $table->float('do_cao')->nullable();
            $table->text('vi_tri')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('o_tieu_chuans');
    }
}
