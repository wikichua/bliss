<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('level', ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug']);
            $table->longText('message')->nullable();
            $table->longText('iteration')->nullable();
            $table->text('user_id')->nullable();
            $table->integer('job_id')->nullable();
        });
        if (\File::exists(base_path('vendor/jenssegers/mongodb'))) {
            try {
                Schema::connection('mongodb')->table('logs', function (Blueprint $table) {
                    $table->index('level');
                    $table->index('user_id');
                    $table->index('job_id');
                    $table->index('created_at');
                    $table->index('updated_at');
                });
            } catch (\MongoDB\Driver\Exception\AuthenticationException $e) {
            }
        }
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('logs');
    }
};
