<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiaDiemSinhVatNgoaiLaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dia_diem_sinh_vat_ngoai_lais', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('dia_diem_id')->nullable();
            $table->foreign('dia_diem_id')->references('id')->on('protected_areas')->onDelete('cascade');
            $table->unsignedInteger('sinh_vat_ngoai_lai_id')->nullable();
            $table->foreign('sinh_vat_ngoai_lai_id')->references('id')->on('sinh_vat_ngoai_lais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dia_diem_sinh_vat_ngoai_lais');
    }
}
