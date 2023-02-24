<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKinhPhiHangNamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kinh_phi_hang_nams', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('nguon_kinh_phi_id')->nullable();
            $table->foreign('nguon_kinh_phi_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->float('tong_kinh_phi')->nullable();
            $table->text('muc_dich_su_dung')->nullable();
            $table->float('ty_le_ngan_sach')->nullable();
            $table->integer('thoi_gian')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->unsignedInteger('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')->references('id')->on('provinces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kinh_phi_hang_nams');
    }
}
