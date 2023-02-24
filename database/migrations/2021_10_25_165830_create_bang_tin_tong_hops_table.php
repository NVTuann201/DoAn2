<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangTinTongHopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bang_tin_tong_hops', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('thong_so_id')->nullable();
            $table->foreign('thong_so_id')->references('id')->on('thong_sos');
            $table->unsignedInteger('phan_cap_id')->nullable();
            $table->foreign('phan_cap_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('chi_so')->nullable();
            $table->float('gia_tri')->nullable();
            $table->string('don_vi_tinh')->nullable();
            $table->string('don_vi_bao_cao')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->string('nguon_du_lieu')->nullable();
            $table->string('ky_bao_cao')->nullable();
            $table->date('bat_dau')->nullable();
            $table->date('ket_thuc')->nullable();
            $table->text('mo_ta')->nullable();
            $table->json('files')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bang_tin_tong_hops');
    }
}
