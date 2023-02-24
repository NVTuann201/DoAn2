<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThongSoQuanTracsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thong_so_quan_tracs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('ten_tieng_anh')->nullable();
            $table->text('ten_tieng_viet')->nullable();
            $table->string('ky_hieu_hoa_hoc')->nullable();
            $table->text('mo_ta')->nullable();
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
        Schema::dropIfExists('thong_so_quan_tracs');
    }
}
