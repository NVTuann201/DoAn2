<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDoiTuongBaoTon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('he_sinh_thais', function (Blueprint $table) {
            $table->string('loai_doi_tuong')->nullable();
            $table->integer('doi_tuong_id')->nullable();
        });
        Schema::table('dataset_resources', function (Blueprint $table) {
            $table->string('loai_doi_tuong')->nullable();
            $table->integer('doi_tuong_id')->nullable();
        });
        Schema::table('bao_cao_ap_lucs', function (Blueprint $table) {
            $table->string('loai_doi_tuong')->nullable();
            $table->integer('doi_tuong_id')->nullable();
        });
        Schema::table('noi_luu_gius', function (Blueprint $table) {
            $table->string('loai_doi_tuong')->nullable();
            $table->integer('doi_tuong_id')->nullable();
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
            $table->dropColumn('loai_doi_tuong');
            $table->dropColumn('doi_tuong_id');
        });
        Schema::table('dataset_resources', function (Blueprint $table) {
            $table->dropColumn('loai_doi_tuong');
            $table->dropColumn('doi_tuong_id');
        });
        Schema::table('bao_cao_ap_lucs', function (Blueprint $table) {
            $table->dropColumn('loai_doi_tuong');
            $table->dropColumn('doi_tuong_id');
        });
        Schema::table('noi_luu_gius', function (Blueprint $table) {
            $table->dropColumn('loai_doi_tuong');
            $table->dropColumn('doi_tuong_id');
        });
    }
}
