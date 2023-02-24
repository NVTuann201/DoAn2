<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiemQuanTracsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diem_quan_tracs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->string('ky_hieu_mau')->nullable();
            $table->unsignedInteger('loai_hinh_id')->nullable();
            $table->foreign('loai_hinh_id')->references('id')->on('loai_hinh_quan_tracs')->onDelete('cascade');
            $table->date('thoi_gian')->nullable();
            $table->unsignedInteger('khu_bao_ton_id')->nullable();
            $table->foreign('khu_bao_ton_id')->references('id')->on('protected_areas');
            $table->text('ket_qua')->nullable();
            $table->text('don_vi_quan_trac')->nullable();
            $table->text('mo_ta')->nullable();
            $table->text('ghi_chu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diem_quan_tracs');
    }
}
