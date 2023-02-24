<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateChuongTrinhDeTai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kiem_soat_sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->string('ma_so')->nullable();
            $table->text('thuoc_chuong_trinh')->nullable();
            $table->text('don_vi_chu_tri')->nullable();
            $table->text('don_vi_phoi_hop')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kiem_soat_sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->dropColumn('ma_so');
            $table->dropColumn('thuoc_chuong_trinh');
            $table->dropColumn('don_vi_chu_tri');
            $table->dropColumn('don_vi_phoi_hop');
        });
    }
}
