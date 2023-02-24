<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_bao_tons', function (Blueprint $table) {
            $table->geometry('geom')->nullable();
        });
        Schema::table('he_sinh_thais', function (Blueprint $table) {
            $table->geometry('geom')->nullable();
        });
        Schema::table('noi_luu_gius', function (Blueprint $table) {
            $table->geometry('geom')->nullable();
        });
        Schema::table('o_tieu_chuans', function (Blueprint $table) {
            $table->geometry('geom')->nullable();
        });
        Schema::table('diem_quan_tracs', function (Blueprint $table) {
            $table->geometry('geom')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_so_bao_tons', function (Blueprint $table) {
            $table->dropColumn('geom');
        });
        Schema::table('he_sinh_thais', function (Blueprint $table) {
            $table->dropColumn('geom');
        });
        Schema::table('noi_luu_gius', function (Blueprint $table) {
            $table->dropColumn('geom');
        });
        Schema::table('o_tieu_chuans', function (Blueprint $table) {
            $table->dropColumn('geom');
        });
        Schema::table('diem_quan_tracs', function (Blueprint $table) {
            $table->dropColumn('geom');
        });
    }
}
