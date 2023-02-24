<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVungChimQuanTrongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vung_chim_quan_trongs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->text('mo_ta')->nullable();
            $table->float('dien_tich')->nullable();
            $table->unsignedInteger('tinh_trang_id')->nullable();
            $table->foreign('tinh_trang_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('can_cu_de_xuat')->nullable();
            $table->text('quyet_dinh_thanh_lap')->nullable();
            $table->date('ngay_thanh_lap')->nullable();
            $table->text('co_quan_ban_hanh')->nullable();
            $table->text('vung_chim_dac_huu')->nullable();
            $table->text('canh_quan_uu_tien')->nullable();
            $table->text('sinh_canh')->nullable();
            $table->unsignedInteger('khu_bao_ton_id')->nullable();
            $table->foreign('khu_bao_ton_id')->references('id')->on('protected_areas');
            $table->unsignedInteger('muc_do_quan_trong_id')->nullable();
            $table->foreign('muc_do_quan_trong_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('hien_trang_quan_ly')->nullable();
            $table->text('phan_cap_quan_ly')->nullable();      
            $table->text('co_quan_quan_ly')->nullable();
            $table->text('hoat_dong_bao_ton')->nullable();
            $table->boolean('quoc_te_cong_nhan')->default(true);
            $table->text('danh_hieu_quoc_te')->nullable();
            $table->date('thoi_gian_cong_nhan')->nullable();
            $table->text('quy_hoach_tinh')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->json('tinh_thanh_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vung_chim_quan_trongs');
    }
}
