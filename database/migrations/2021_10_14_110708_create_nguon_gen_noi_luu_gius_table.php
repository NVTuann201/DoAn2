<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonGenNoiLuuGiusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguon_gen_noi_luu_gius', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('nguon_gen_id');
            $table->foreign('nguon_gen_id')->references('id')->on('nguon_gens')->onDelete('cascade');
            $table->unsignedInteger('noi_luu_giu_id');
            $table->foreign('noi_luu_giu_id')->references('id')->on('noi_luu_gius')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nguon_gen_noi_luu_gius');
    }
}
