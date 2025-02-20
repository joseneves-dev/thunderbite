<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->integer('daily_volume')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->dropColumn('daily_volume');
        });
    }
};
