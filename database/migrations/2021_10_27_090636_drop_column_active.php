<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nhom_chi_this', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('chi_this', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('thong_sos', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('loai_hinh_quan_tracs', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('thong_so_quan_tracs', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('van_ban_phap_luats', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('giay_phep_da_dang_sinh_hocs', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('hop_tac_quoc_tes', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('mo_hinh_sang_kiens', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('kiem_soat_sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nhom_chi_this', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('chi_this', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('thong_sos', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('loai_hinh_quan_tracs', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('thong_so_quan_tracs', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('van_ban_phap_luats', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('giay_phep_da_dang_sinh_hocs', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('hop_tac_quoc_tes', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('mo_hinh_sang_kiens', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('kiem_soat_sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
    }
}
