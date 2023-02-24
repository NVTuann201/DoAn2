<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGiayPhep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('giay_phep_da_dang_sinh_hocs', function (Blueprint $table) {
            $table->unsignedInteger('giay_phep_id')->nullable();
            $table->foreign('giay_phep_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('doi_tuong_id')->nullable();
            $table->foreign('doi_tuong_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('muc_dich_id')->nullable();
            $table->foreign('muc_dich_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('ten')->nullable();
            $table->text('ten_khoa_hoc')->nullable();
            $table->text('mau_nguon_gen')->nullable();
            $table->text('so_hieu')->nullable();
            $table->date('ngay_hieu_luc')->nullable();
            $table->text('dia_chi')->nullable();
            $table->text('chung_loai')->nullable();
            $table->float('so_luong')->nullable();
            $table->text('khoi_luong')->nullable();
            $table->text('don_vi_cung_cap')->nullable();
            $table->text('dac_diem')->nullable();
            $table->text('cach_tiep_can')->nullable();
            $table->text('su_kien_chuyen_gen')->nullable();
            $table->text('tinh_trang_lien_quan')->nullable();
            $table->text('ma')->nullable();
            $table->text('dac_diem_khai_thac')->nullable();
            $table->text('phuong_tien_khai_thac')->nullable();
            $table->text('hinh_thuc_khai_thac')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('giay_phep_da_dang_sinh_hocs', function (Blueprint $table) {
            $table->dropColumn('giay_phep_id');
            $table->dropColumn('doi_tuong_id');
            $table->dropColumn('muc_dich_id');
            $table->dropColumn('ten');
            $table->dropColumn('ten_khoa_hoc');
            $table->dropColumn('mau_nguon_gen');
            $table->dropColumn('so_hieu');
            $table->dropColumn('ngay_hieu_luc');
            $table->dropColumn('dia_chi');
            $table->dropColumn('chung_loai');
            $table->dropColumn('so_luong');
            $table->dropColumn('khoi_luong');
            $table->dropColumn('don_vi_cung_cap');
            $table->dropColumn('dac_diem');
            $table->dropColumn('cach_tiep_can');
            $table->dropColumn('su_kien_chuyen_gen');
            $table->dropColumn('tinh_trang_lien_quan');
            $table->dropColumn('ma');
            $table->dropColumn('dac_diem_khai_thac');
            $table->dropColumn('phuong_tien_khai_thac');
            $table->dropColumn('hinh_thuc_khai_thac');
        });
    }
}
