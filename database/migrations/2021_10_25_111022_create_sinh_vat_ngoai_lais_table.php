<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSinhVatNgoaiLaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->string('ten_khoa_hoc')->nullable();
            $table->string('nguon_goc')->nullable();
            $table->unsignedInteger('nguy_co_id')->nullable();
            $table->foreign('nguy_co_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('phan_loai_id')->nullable();
            $table->foreign('phan_loai_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->float('dien_tich_phan_bo')->nullable();
            $table->float('mat_do')->nullable();
            $table->string('don_vi_tinh_mat_do')->nullable();
            $table->text('noi_phan_bo')->nullable();
            $table->integer('thoi_gian')->nullable();
            $table->text('nguon_du_lieu')->null();
            $table->text('ghi_chu')->null();
            $table->json('tinh_thanh_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sinh_vat_ngoai_lais');
    }
}
