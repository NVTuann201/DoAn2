<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKiemSoatSinhVatNgoaiLaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kiem_soat_sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->date('thoi_gian_bat_dau')->nullable();
            $table->date('thoi_gian_ket_thuc')->nullable();
            $table->string('don_vi_thuc_hien')->nullable();
            $table->string('san_pham_chinh')->nullable();
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
        Schema::dropIfExists('kiem_soat_sinh_vat_ngoai_lais');
    }
}
