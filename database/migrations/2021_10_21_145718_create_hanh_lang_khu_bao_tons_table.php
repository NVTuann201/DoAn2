<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHanhLangKhuBaoTonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hanh_lang_khu_bao_tons', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('khu_bao_ton_id')->nullable();
            $table->foreign('khu_bao_ton_id')->references('id')->on('protected_areas')->onDelete('cascade');
            $table->unsignedInteger('hanh_lang_id')->nullable();
            $table->foreign('hanh_lang_id')->references('id')->on('hanh_lang_da_dangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hanh_lang_khu_bao_tons');
    }
}
