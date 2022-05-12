<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icon')->nullable()->default('');
            $table->string('link')->nullable();
            $table->text('message');
            $table->timestamps();
            $table->integer('sender_id')->default(0); // 0 - everyone
            $table->integer('receiver_id')->default(0); // 0 - everyone
            $table->string('status', 1)->default('U'); // R - read / U - unread

            $table->index('status');
            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index(['sender_id', 'receiver_id']);
        });

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

    public function down()
    {
        // drop tables
        Schema::dropIfExists('alerts');
    }
};
