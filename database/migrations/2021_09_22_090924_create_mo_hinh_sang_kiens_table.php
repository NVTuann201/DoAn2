<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoHinhSangKiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mo_hinh_sang_kiens', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->integer('hinh_thuc_id')->nullable();
            $table->foreign('hinh_thuc_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('co_quan_to_chuc')->nullable();
            $table->string('ket_qua_ap_dung')->nullable();
            $table->integer('nam_thuc_hien')->nullable();
            $table->integer('phan_loai_id')->nullable();
            $table->foreign('phan_loai_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('dia_diem_to_chuc')->nullable();
            $table->unsignedInteger('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->json('files')->nullable();
            $table->text('noi_dung')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mo_hinh_sang_kiens');
    }
}
