<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('icon')->nullable()->default('');
            $table->string('link')->nullable();
            $table->text('message');
            $table->timestamps();
            $table->string('sender_id'); // 0 - everyone
            $table->string('receiver_id'); // 0 - everyone
            $table->string('status', 1)->default('U'); // R - read / U - unread

            $table->index('status');
            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index(['sender_id', 'receiver_id']);
        });
        if (\File::exists(base_path('vendor/jenssegers/mongodb'))) {
            try {
                Schema::connection('mongodb')->table('alerts', function (Blueprint $table) {
                    $table->index('status');
                    $table->index('sender_id');
                    $table->index('receiver_id');
                    $table->index(['sender_id', 'receiver_id']);
                });
            } catch (\MongoDB\Driver\Exception\AuthenticationException $e) {
            }
        }
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('alerts');
    }
};
