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
        if (! Schema::hasColumn('preparations', 'preparation_banner')) {
            Schema::table('preparations', function (Blueprint $table) {
                $table->string('preparation_banner')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('preparations', 'preparation_banner')) {
            Schema::table('preparations', function (Blueprint $table) {
                $table->dropColumn('preparation_banner');
            });
        }
    }
};
