<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoaiGensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_gens', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('nhom_gen_id')->nullable();
            $table->foreign('nhom_gen_id')->references('id')->on('nhom_gens');
            $table->text('ten')->nullable();
            $table->text('mo_ta')->nullable();
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
        Schema::dropIfExists('loai_gens');
    }
}
