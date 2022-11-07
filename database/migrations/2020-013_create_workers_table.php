<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->ulid('id');
            $table->text('batch');
            $table->text('queue');
            $table->boolean('attempted')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
        if (\File::exists(base_path('vendor/jenssegers/mongodb'))) {
            try {
                Schema::connection('mongodb')->table('workers', function (Blueprint $table) {
                    $table->index('queue');
                    $table->index('batch');
                    $table->index('attempted');
                    $table->index('created_at');
                });
            } catch (\MongoDB\Driver\Exception\AuthenticationException $e) {
            }
        }
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('workers');
    }
};
