<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->string('child_photo')->nullable()->after('parent_mobile');
        });
    }

    public function down()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->dropColumn('child_photo');
        });
    }
};