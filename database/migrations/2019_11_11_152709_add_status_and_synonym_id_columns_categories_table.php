<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAndSynonymIdColumnsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kingdoms', function (Blueprint $table) {
            $table->string('status')->default('accepted');
            $table->unsignedInteger('synonym_id')->nullable();
            $table->foreign('synonym_id')->references('id')->on('kingdoms')->onDelete('cascade');
        });

        Schema::table('phylums', function (Blueprint $table) {
            $table->string('status')->default('accepted');
            $table->unsignedInteger('synonym_id')->nullable();
            $table->foreign('synonym_id')->references('id')->on('phylums')->onDelete('cascade');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->string('status')->default('accepted');
            $table->unsignedInteger('synonym_id')->nullable();
            $table->foreign('synonym_id')->references('id')->on('classes')->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('accepted');
            $table->unsignedInteger('synonym_id')->nullable();
            $table->foreign('synonym_id')->references('id')->on('orders')->onDelete('cascade');
        });

        Schema::table('families', function (Blueprint $table) {
            $table->string('status')->default('accepted');
            $table->unsignedInteger('synonym_id')->nullable();
            $table->foreign('synonym_id')->references('id')->on('families')->onDelete('cascade');
        });

        Schema::table('genus', function (Blueprint $table) {
            $table->string('status')->default('accepted');
            $table->unsignedInteger('synonym_id')->nullable();
            $table->foreign('synonym_id')->references('id')->on('genus')->onDelete('cascade');
        });

        Schema::table('species', function (Blueprint $table) {
            $table->string('status')->default('accepted');
            $table->unsignedInteger('synonym_id')->nullable();
            $table->foreign('synonym_id')->references('id')->on('species')->onDelete('cascade');
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
            $table->dropColumn('status');
            $table->dropColumn('synonym_id');
        });

        Schema::table('phylums', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('synonym_id');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('synonym_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('synonym_id');
        });

        Schema::table('families', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('synonym_id');
        });

        Schema::table('genus', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('synonym_id');
        });

        Schema::table('species', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('synonym_id');
        });
    }
}
