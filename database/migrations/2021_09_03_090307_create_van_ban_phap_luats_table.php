<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVanBanPhapLuatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('van_ban_phap_luats', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('loai_id')->nullable();
            $table->foreign('loai_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('ten')->nullable();
            $table->string('so_hieu')->nullable();
            $table->unsignedInteger('co_quan_ban_hanh_id')->nullable();
            $table->foreign('co_quan_ban_hanh_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->date('ngay_ban_hanh')->nullable();
            $table->date('ngay_hieu_luc')->nullable();
            $table->unsignedInteger('linh_vuc_id')->nullable();
            $table->foreign('linh_vuc_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->boolean('hieu_luc')->default(true);
            $table->json('files')->nullable();
            $table->text('noi_dung_chinh')->nullable();
            $table->text('noi_dung_toan_van')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('van_ban_phap_luats');
    }
}
