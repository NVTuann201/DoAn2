<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDichVuHeSinhThaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dich_vu_he_sinh_thais', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->unsignedInteger('phan_loai_id')->nullable();
            $table->foreign('phan_loai_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->float('gia_tri_luong_gia')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->text('mo_ta')->nullable();
            $table->string('nguon_du_lieu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dich_vu_he_sinh_thais');
    }
}
