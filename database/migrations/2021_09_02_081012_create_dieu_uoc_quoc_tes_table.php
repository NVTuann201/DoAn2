<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDieuUocQuocTesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dieu_uoc_quoc_tes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten');
            $table->date('ngay_ban_hanh')->nullable();
            $table->date('ngay_hieu_luc')->nullable();
            $table->date('ngay_viet_nam_tham_gia')->nullable();
            $table->integer('so_quoc_gia_tham_gia')->nullable();
            $table->boolean('hieu_luc')->default(true);
            $table->json('files')->nullable();
            $table->text('noi_dung_chinh')->nullable();
            $table->text('noi_dung_toan_van')->nullable();
            $table->text('ghi_chu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dieu_uoc_quoc_tes');
    }
}
