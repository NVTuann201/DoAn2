<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiayPhepDaDangSinhHocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giay_phep_da_dang_sinh_hocs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('ten_giay_phep_id')->nullable();
            $table->foreign('ten_giay_phep_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('co_quan_cap')->nullable();
            $table->date('ngay_cap')->nullable();
            $table->unsignedInteger('loai_giay_phep_id')->nullable();
            $table->foreign('loai_giay_phep_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('don_vi_duoc_cap')->nullable();
            $table->date('ngay_het_han')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->boolean('active')->default(true);
            $table->json('files')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('giay_phep_da_dang_sinh_hocs');
    }
}
