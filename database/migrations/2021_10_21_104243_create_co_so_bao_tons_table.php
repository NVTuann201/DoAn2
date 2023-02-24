<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoSoBaoTonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_so_bao_tons', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('ten')->nullable();
            $table->text('dia_chi')->nullable();
            $table->text('co_quan_chu_quan')->nullable();
            $table->text('mo_ta')->nullable();
            $table->text('giay_phep')->nullable();
            $table->date('ngay_cap')->nullable();
            $table->unsignedInteger('quan_ly_id')->nullable();
            $table->foreign('quan_ly_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('dia_diem_id')->nullable();
            $table->foreign('dia_diem_id')->references('id')->on('protected_areas');
            $table->float('dien_tich')->nullable();
            $table->unsignedInteger('loai_hinh_id')->nullable();
            $table->foreign('loai_hinh_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('loai_hinh_to_chuc_id')->nullable();
            $table->foreign('loai_hinh_to_chuc_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('chuc_nang')->nullable();
            $table->text('co_so_vat_chat')->nullable();
            $table->text('don_vi_cap')->nullable();
            $table->text('quy_trinh_ky_thuat')->nullable();
            $table->text('nhan_luc')->nullable();
            $table->text('tai_chinh')->nullable();
            $table->text('hinh_thuc_luu_giu')->nullable();
            $table->float('dien_tich_luu_giu')->nullable();
            $table->unsignedInteger('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')->references('id')->on('provinces')->onDelete('cascade');
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
        Schema::dropIfExists('co_so_bao_tons');
    }
}
