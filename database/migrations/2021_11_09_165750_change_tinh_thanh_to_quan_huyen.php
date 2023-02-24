<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTinhThanhToQuanHuyen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hop_tac_quoc_tes', function (Blueprint $table) {
            $table->unsignedInteger('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')->references('id')->on('districts')->onDelete('cascade');
        });
        Schema::table('mo_hinh_sang_kiens', function (Blueprint $table) {
            $table->unsignedInteger('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')->references('id')->on('districts')->onDelete('cascade');
        });
        Schema::table('kiem_soat_sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->unsignedInteger('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')->references('id')->on('districts')->onDelete('cascade');
        });
        Schema::table('kinh_phi_hang_nams', function (Blueprint $table) {
            $table->unsignedInteger('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')->references('id')->on('districts')->onDelete('cascade');
        });
        Schema::table('co_so_bao_tons', function (Blueprint $table) {
            $table->unsignedInteger('quan_huyen_id')->nullable();
            $table->foreign('quan_huyen_id')->references('id')->on('districts')->onDelete('cascade');
        });
        Schema::table('hanh_lang_da_dangs', function (Blueprint $table) {
            $table->json('quan_huyen')->nullable();
        });
        Schema::table('dat_ngap_nuocs', function (Blueprint $table) {
            $table->json('quan_huyen')->nullable();
        });
        Schema::table('vung_chim_quan_trongs', function (Blueprint $table) {
            $table->json('quan_huyen')->nullable();
        });
        Schema::table('khu_canh_quan_sinh_thais', function (Blueprint $table) {
            $table->json('quan_huyen')->nullable();
        });
        Schema::table('sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->json('quan_huyen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hop_tac_quoc_tes', function (Blueprint $table) {
            $table->dropColumn('quan_huyen_id');
        });
        Schema::table('mo_hinh_sang_kiens', function (Blueprint $table) {
            $table->dropColumn('quan_huyen_id');
        });
        Schema::table('kiem_soat_sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->dropColumn('quan_huyen_id');
        });
        Schema::table('kinh_phi_hang_nams', function (Blueprint $table) {
            $table->dropColumn('quan_huyen_id');
        });
        Schema::table('co_so_bao_tons', function (Blueprint $table) {
            $table->dropColumn('quan_huyen_id');
        });
        Schema::table('hanh_lang_da_dangs', function (Blueprint $table) {
            $table->dropColumn('quan_huyen');
        });
        Schema::table('dat_ngap_nuocs', function (Blueprint $table) {
            $table->dropColumn('quan_huyen');
        });
        Schema::table('vung_chim_quan_trongs', function (Blueprint $table) {
            $table->dropColumn('quan_huyen');
        });
        Schema::table('khu_canh_quan_sinh_thais', function (Blueprint $table) {
            $table->dropColumn('quan_huyen');
        });
        Schema::table('sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->dropColumn('quan_huyen');
        });
    }
}
