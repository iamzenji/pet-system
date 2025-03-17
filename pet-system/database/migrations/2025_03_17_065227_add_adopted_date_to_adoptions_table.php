<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('adoptions', function (Blueprint $table) {
            $table->date('adopted_date')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('adoptions', function (Blueprint $table) {
            $table->dropColumn('adopted_date');
        });
    }
};
