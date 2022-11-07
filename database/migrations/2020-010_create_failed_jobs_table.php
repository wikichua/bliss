<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->dropColumn(['id']);
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->text('batch')->nullable()->after('connection');
            $table->ulid('id')->first();
        });
    }

    public function down()
    {
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->dropColumn(['batch']);
        });
    }
};
