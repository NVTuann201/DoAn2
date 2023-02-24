<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColResourceIdToSomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kingdoms', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
        Schema::table('phylums', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
        Schema::table('classes', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
        Schema::table('families', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });

        Schema::table('genus', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
        Schema::table('species', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
        Schema::table('darwin_core_taxons', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
        Schema::table('darwin_core_taxon_tree', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
        Schema::table('darwin_core_occurrences', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
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
            $table->dropColumn('resource_id');
        });
        Schema::table('phylums', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });
        Schema::table('families', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });

        Schema::table('genus', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });
        Schema::table('species', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });

        Schema::table('darwin_core_taxons', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });
        Schema::table('darwin_core_taxon_tree', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });
        Schema::table('darwin_core_occurrences', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });

    }
}
