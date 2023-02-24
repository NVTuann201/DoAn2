<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBoXungSinhVatNgoaiLai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->text('dac_diem_hinh_thai')->nullable();
            $table->text('dac_diem_sinh_thai')->nullable();
            $table->text('kinh_nghiem_phong_ngua')->nullable();
            $table->text('phan_bo_viet_nam')->nullable();
            $table->text('ghi_nhan_the_gioi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->dropColumn('dac_diem_hinh_thai');
            $table->dropColumn('dac_diem_sinh_thai');
            $table->dropColumn('kinh_nghiem_phong_ngua');
            $table->dropColumn('phan_bo_viet_nam');
            $table->dropColumn('ghi_nhan_the_gioi');
        });
    }
}
