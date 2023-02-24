<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriThucTruyenThongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tri_thuc_truyen_thongs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->string('nguoi_luu_giu')->nullable();
            $table->string('noi_luu_giu')->nullable();
            $table->text('mo_ta')->nullable();
            $table->text('cap_giay_chung_nhan')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->unsignedInteger('nhom_id')->nullable();
            $table->foreign('nhom_id')->references('id')->on('lookups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tri_thuc_truyen_thongs');
    }
}
