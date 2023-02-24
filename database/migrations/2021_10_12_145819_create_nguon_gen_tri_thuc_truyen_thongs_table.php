<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonGenTriThucTruyenThongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguon_gen_tri_thuc_truyen_thongs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('nguon_gen_id');
            $table->foreign('nguon_gen_id')->references('id')->on('nguon_gens')->onDelete('cascade');
            $table->unsignedInteger('tri_thuc_truyen_thong_id');
            $table->foreign('tri_thuc_truyen_thong_id')->references('id')->on('tri_thuc_truyen_thongs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nguon_gen_tri_thuc_truyen_thongs');
    }
}
