<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDienTichKhuBaoTon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('protected_areas', function (Blueprint $table) {
            $table->double('dien_tich_vung_loi')->nullable();
            $table->double('dien_tich_vung_dem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('protected_areas', function (Blueprint $table) {
            $table->dropColumn('dien_tich_vung_loi');
            $table->dropColumn('dien_tich_vung_dem');
        });
    }
}
