<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaoCaoApLucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bao_cao_ap_lucs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('chi_thi_id')->nullable();
            $table->foreign('chi_thi_id')->references('id')->on('chi_this');
            $table->double('gia_tri')->nullable();
            $table->string('don_vi_tinh')->nullable();
            $table->text('mo_ta')->nullable();
            $table->unsignedInteger('phan_cap_id')->nullable();
            $table->foreign('phan_cap_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('don_vi_bao_cao')->nullable();
            $table->text('ky_bao_cao')->nullable();
            $table->date('bat_dau')->nullable();
            $table->date('ket_thuc')->nullable();
            $table->text('nguon_du_lieu')->nullable();
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
        Schema::dropIfExists('bao_cao_ap_lucs');
    }
}
