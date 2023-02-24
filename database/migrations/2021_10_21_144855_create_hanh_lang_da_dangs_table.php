<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHanhLangDaDangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hanh_lang_da_dangs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->float('dien_tich')->nullable();
            $table->float('chieu_dai')->nullable();
            $table->text('chuc_nang')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->text('mo_ta')->nullable();
            $table->string('quyet_dinh_thanh_lap')->nullable();
            $table->date('ngay_thanh_lap')->nullable();
            $table->string('co_quan_ban_hanh')->nullable();
            $table->unsignedInteger('phan_cap_quan_ly_id')->nullable();
            $table->foreign('phan_cap_quan_ly_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('co_quan_quan_ly')->nullable();
            $table->string('muc_dich_thanh_lap')->nullable();
            $table->string('ky_quy_hoach')->nullable();
            $table->string('quy_hoach_tinh')->nullable();
            $table->string('bien_dong')->nullable();
            $table->unsignedInteger('tinh_trang_id')->nullable();
            $table->foreign('tinh_trang_id')->references('id')->on('lookups')->onDelete('cascade');
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
        Schema::dropIfExists('hanh_lang_da_dangs');
    }
}
