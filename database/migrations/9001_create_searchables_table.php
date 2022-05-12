<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('searchables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model');
            $table->integer('model_id');
            $table->json('tags');
            $table->timestamps();

            $table->index(['model']);
            $table->index(['model', 'model_id']);
        });
        try {
            Schema::connection('mongodb')->table('searchables', function (Blueprint $table) {
                $table->index(['model']);
                $table->index(['model', 'model_id']);
            });
        } catch (\MongoDB\Driver\Exception\AuthenticationException $e) {

        }
    }

    public function down()
    {
        Schema::dropIfExists('searchables');
    }
};
