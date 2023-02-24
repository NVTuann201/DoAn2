<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhuDuTruSinhQuyensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khu_du_tru_sinh_quyens', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->text('mo_ta')->nullable();
            $table->float('dien_tich')->nullable();
            $table->unsignedInteger('tinh_trang_id')->nullable();
            $table->foreign('tinh_trang_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->float('dan_so')->nullable();
            $table->text('quyet_dinh_thanh_lap')->nullable();
            $table->date('ngay_thanh_lap')->nullable();
            $table->text('co_quan_ban_hanh')->nullable();
            $table->text('co_quan_quan_ly')->nullable();
            $table->unsignedInteger('khu_bao_ton_id')->nullable();
            $table->foreign('khu_bao_ton_id')->references('id')->on('protected_areas');
            $table->string('vung_loi')->nullable();
            $table->float('dien_tich_vung_loi')->nullable();
            $table->integer('dan_so_vung_loi')->nullable();
            $table->string('vung_dem')->nullable();
            $table->float('dien_tich_vung_dem')->nullable();
            $table->integer('dan_so_vung_dem')->nullable();
            $table->string('vung_chuyen_tiep')->nullable();
            $table->float('dien_tich_vung_chuyen_tiep')->nullable();
            $table->integer('dan_so_vung_chuyen_tiep')->nullable();
            $table->boolean('quoc_te_cong_nhan')->default(true);
            $table->text('danh_hieu_quoc_te')->nullable();
            $table->date('thoi_gian_cong_nhan')->nullable();
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
        Schema::dropIfExists('khu_du_tru_sinh_quyens');
    }
}
