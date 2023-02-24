<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnqueInAllRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kingdoms', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->unique(['name', 'resource_id']);
        });
        Schema::table('phylums', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->unique(['name', 'resource_id']);
        });
        Schema::table('classes', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->unique(['name', 'resource_id']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->unique(['name', 'resource_id']);
        });
        Schema::table('families', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->unique(['name', 'resource_id']);
        });
        Schema::table('genus', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->unique(['name', 'resource_id']);
        });
        Schema::table('species', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->unique(['name', 'resource_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kingdoms', function (Blueprint $table) {
            $table->unique(['name']);
            $table->dropUnique(['name', 'resource_id']);

        });
        Schema::table('phylums', function (Blueprint $table) {
            $table->unique(['name']);
            $table->dropUnique(['name', 'resource_id']);
        });
        Schema::table('classes', function (Blueprint $table) {
            $table->unique(['name']);
            $table->dropUnique(['name', 'resource_id']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->unique(['name']);
            $table->dropUnique(['name', 'resource_id']);
        });
        Schema::table('families', function (Blueprint $table) {
            $table->unique(['name']);
            $table->dropUnique(['name', 'resource_id']);
        });
        Schema::table('genus', function (Blueprint $table) {
            $table->unique(['name']);
            $table->dropUnique(['name', 'resource_id']);
        });
        Schema::table('species', function (Blueprint $table) {
            $table->unique(['name']);
            $table->dropUnique(['name', 'resource_id']);
        });

    }
}
