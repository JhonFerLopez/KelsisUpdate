<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preparation_translations', function (Blueprint $table) {
            $table->integer('locale_id')->nullable()->unsigned();
        });

        Schema::table('preparation_translations', function (Blueprint $table) {
            $table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preparation_translations', function (Blueprint $table) {
            $table->dropForeign(['locale_id']);

            $table->dropColumn('locale_id');
        });
    }
};
