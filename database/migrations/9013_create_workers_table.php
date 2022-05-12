<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->text('batch');
            $table->text('queue');
            $table->timestamp('created_at')->useCurrent();
        });
        try {
            Schema::connection('mongodb')->table('workers', function (Blueprint $table) {
                $table->index('queue');
                $table->index('batch');
                $table->index('created_at');
            });
        } catch (\MongoDB\Driver\Exception\AuthenticationException $e) {

        }
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('workers');
    }
};
