<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHeSinhThai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('he_sinh_thais', function (Blueprint $table) {
            $table->text('ky_bao_cao')->nullable();
            $table->date('bat_dau')->nullable();
            $table->date('ket_thuc')->nullable();
            $table->double('dien_tich_rung_dac_dung')->nullable();
            $table->double('dien_tich_rung_tu_nhien')->nullable();
            $table->double('dien_tich_rung_trong')->nullable();
            $table->renameColumn('dien_tich_moi_phuc_hoi', 'dien_tich_rung_trong_moi');
            $table->double('dien_tich_rung_chuyen_doi')->nullable();
            $table->double('dien_tich_dnn_chuyen_doi')->nullable();
            $table->double('dien_tich_rung_ngap_nuoc')->nullable();
            $table->double('dien_tich_song_ngoi')->nullable();
            $table->double('dien_tich_dat_mat_nuoc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('he_sinh_thais', function (Blueprint $table) {
            $table->dropColumn('ky_bao_cao');
            $table->dropColumn('bat_dau');
            $table->dropColumn('ket_thuc');
            $table->dropColumn('dien_tich_rung_dac_dung');
            $table->dropColumn('dien_tich_rung_tu_nhien');
            $table->dropColumn('dien_tich_rung_trong');
            $table->dropColumn('dien_tich_moi_phuc_hoi', 'dien_tich_rung_trong_moi');
            $table->dropColumn('dien_tich_rung_chuyen_doi');
            $table->dropColumn('dien_tich_dnn_chuyen_doi');
            $table->dropColumn('dien_tich_rung_ngap_nuoc');
            $table->dropColumn('dien_tich_song_ngoi');
            $table->dropColumn('dien_tich_dat_mat_nuoc');
        });
    }
}
