<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhuCanhQuanSinhThaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khu_canh_quan_sinh_thais', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->text('mo_ta')->nullable();
            $table->float('dien_tich')->nullable();
            $table->unsignedInteger('tinh_trang_id')->nullable();
            $table->foreign('tinh_trang_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('van_ban_dieu_chinh')->nullable();
            $table->text('quyet_dinh_thanh_lap')->nullable();
            $table->date('ngay_thanh_lap')->nullable();
            $table->text('co_quan_ban_hanh')->nullable();
            $table->text('co_quan_quan_ly')->nullable();
            $table->text('phan_cap_quan_ly')->nullable();
            $table->text('de_xuat_quan_ly')->nullable();
            $table->boolean('quoc_te_cong_nhan')->default(true);
            $table->text('danh_hieu_quoc_te')->nullable();
            $table->date('thoi_gian_cong_nhan')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->json('tinh_thanh_id')->nullable();
            $table->unsignedInteger('muc_do_quan_trong_id')->nullable();
            $table->foreign('muc_do_quan_trong_id')->references('id')->on('lookups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('khu_canh_quan_sinh_thais');
    }
}
