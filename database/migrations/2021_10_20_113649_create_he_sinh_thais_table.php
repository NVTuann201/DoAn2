<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeSinhThaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('he_sinh_thais', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('he_sinh_thai_lookup_id')->nullable();
            $table->foreign('he_sinh_thai_lookup_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('ten')->nullable();
            $table->float('dien_tich')->nullable();
            $table->float('dien_tich_chuyen_doi')->nullable();
            $table->unsignedInteger('dia_diem_id')->nullable();
            $table->foreign('dia_diem_id')->references('id')->on('protected_areas')->onDelete('cascade');
            $table->unsignedInteger('phan_loai_id')->nullable();
            $table->foreign('phan_loai_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('nguon_goc_id')->nullable();
            $table->foreign('nguon_goc_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('phan_loai_rung_id')->nullable();
            $table->foreign('phan_loai_rung_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('phan_loai_he_sinh_thai_id')->nullable();
            $table->foreign('phan_loai_he_sinh_thai_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->float('do_phu')->nullable();
            $table->float('tru_luong')->nullable();
            $table->string('don_vi_tinh_tru_luong')->nullable();
            $table->float('dien_tich_moi_phuc_hoi')->nullable();
            $table->float('dien_tich_moi_chet')->nullable();
            $table->float('dien_tich_rung_phong_ho')->nullable();
            $table->float('dien_tich_rung_fsc')->nullable();
            $table->float('dien_tich_rung_dvtm')->nullable();
            $table->string('nguon_tai_lieu')->nullable();
            $table->string('mo_ta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('he_sinh_thais');
    }
}
