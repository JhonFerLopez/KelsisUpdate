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
        Schema::create('preparation_filterable_attributes', function (Blueprint $table) {
            $table->integer('preparation_id')->unsigned();
            $table->integer('attribute_id')->unsigned();
            $table->foreign('preparation_id')->references('id')->on('preparations')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preparation_filterable_attributes');
    }
};
