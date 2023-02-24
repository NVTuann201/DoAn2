<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProtectedArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('protected_areas', function (Blueprint $table) {
            $table->float('m_area')->nullable();
            $table->string('status_no')->nullable();
            $table->string('plan_in')->nullable();
            $table->text('change')->nullable();
            $table->text('province_plan')->nullable();
            $table->string('purpose')->nullable();
            $table->text('management_proposal')->nullable();
         //   $table->string('international_criteria')->nullable();
            $table->text('habitat_types')->nullable();
            $table->text('unique_flora')->nullable();
            $table->text('unique_fauna')->nullable();
            $table->text('biological_richness')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('protected_areas', function (Blueprint $table) {
            $table->dropColumn('m_area');
            $table->dropColumn('status_no');
            $table->dropColumn('plan_in');
            $table->dropColumn('change');
            $table->dropColumn('province_plan');
            $table->dropColumn('purpose');
            $table->dropColumn('management_proposal');
            $table->dropColumn('international_criteria');
            $table->dropColumn('habitat_types');
            $table->dropColumn('unique_flora');
            $table->dropColumn('unique_fauna');
            $table->dropColumn('biological_richness');
        });
    }
}
