<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonGensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguon_gens', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('loai_gen_id')->nullable();
            $table->foreign('loai_gen_id')->references('id')->on('loai_gens');
            $table->unsignedInteger('nhom_gen_id')->nullable();
            $table->foreign('nhom_gen_id')->references('id')->on('nhom_gens');
            $table->string('ten')->nullable();
            $table->string('ten_thong_thuong')->nullable();
            $table->string('ten_dan_toc')->nullable();
            $table->string('ten_khoa_hoc')->nullable();
            $table->text('dac_diem')->nullable();
            $table->unsignedInteger('su_dung_id')->nullable();
            $table->foreign('su_dung_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('gen_quy_hiem_id')->nullable();
            $table->foreign('gen_quy_hiem_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('ma_so_quoc_gia')->nullable();
            $table->string('ma_so_tinh')->nullable();
            $table->unsignedInteger('tinh_trang_luu_giu_id')->nullable();
            $table->foreign('tinh_trang_luu_giu_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('tinh_trang_nghien_cuu_id')->nullable();
            $table->foreign('tinh_trang_nghien_cuu_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('tinh_trang_su_dung_id')->nullable();
            $table->foreign('tinh_trang_su_dung_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('loai_id')->nullable();
            $table->foreign('loai_id')->references('id')->on('species');
            $table->unsignedInteger('nguon_goc_viet_nam_id')->nullable();
            $table->foreign('nguon_goc_viet_nam_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('nguon_goc_dia_phuong_id')->nullable();
            $table->foreign('nguon_goc_dia_phuong_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('nguon_goc_co_so_id')->nullable();
            $table->foreign('nguon_goc_co_so_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('xuat_xu')->nullable();
            $table->unsignedInteger('phuong_thuc_luu_giu_id')->nullable();
            $table->foreign('phuong_thuc_luu_giu_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->double('dien_tich_luu_giu')->nullable();
            $table->text('vat_lieu_di_truyen_luu_giu')->nullable();
            $table->double('so_luong_vat_lieu_di_truyen_luu_giu')->nullable();
            $table->integer('nam_bat_dau_luu_tru')->nullable();
            $table->text('hinh_thuc_bao_quan')->nullable();
            $table->unsignedInteger('tinh_trang_id')->nullable();
            $table->foreign('tinh_trang_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('bien_phap_bao_ton')->nullable();
            $table->text('kha_nang_tiep_can')->nullable();
            $table->text('quy_trinh_tiep_can')->nullable();
            $table->unsignedInteger('gen_co_dieu_kien_id')->nullable();
            $table->foreign('gen_co_dieu_kien_id')->references('id')->on('lookups')->onDelete('cascade');
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
        Schema::dropIfExists('nguon_gens');
    }
}
