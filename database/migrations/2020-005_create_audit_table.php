<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('user_id')->nullable();
            $table->string('model_id')->nullable();
            $table->string('model_class')->nullable();
            $table->string('message');
            $table->json('data')->nullable();
            $table->json('agents')->nullable();
            $table->string('opendns')->nullable();
            $table->json('iplocation')->nullable();
            $table->timestamp('created_at');

            $table->index('user_id');
            $table->index('model_id');
            $table->index('model_class');
            $table->index('opendns');
            $table->index('created_at');
            $table->index(['model_id', 'model_class']);
            $table->index(['user_id', 'model_id', 'model_class']);
        });
        if (\File::exists(base_path('vendor/jenssegers/mongodb'))) {
            try {
                Schema::connection('mongodb')->table('audits', function (Blueprint $table) {
                    $table->index('user_id');
                    $table->index('model_id');
                    $table->index('model_class');
                    $table->index('opendns');
                    $table->index('created_at');
                    $table->index(['model_id', 'model_class']);
                    $table->index(['user_id', 'model_id', 'model_class']);
                });
            } catch (\MongoDB\Driver\Exception\AuthenticationException $e) {
            }
        }
    }

    public function down()
    {
        // drop table
        Schema::dropIfExists('audits');
    }
};
