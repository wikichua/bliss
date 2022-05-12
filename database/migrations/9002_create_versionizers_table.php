<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('versionizers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mode');
            $table->string('model_class');
            $table->integer('model_id');
            $table->json('data');
            $table->json('changes');
            $table->timestamp('reverted_at')->nullable();
            $table->integer('reverted_by')->nullable();
            $table->timestamps();

            $table->index(['mode']);
            $table->index(['model_id']);
            $table->index(['model_class']);
            $table->index(['model_class', 'model_id']);
        });

        try {
            Schema::connection('mongodb')->table('versionizers', function (Blueprint $table) {
                $table->index(['mode']);
                $table->index(['model_id']);
                $table->index(['model_class']);
                $table->index(['model_class', 'model_id']);
            });
        } catch (\MongoDB\Driver\Exception\AuthenticationException $e) {

        }
    }

    public function down()
    {
        Schema::dropIfExists('versionizers');
    }
};
