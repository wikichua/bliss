<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('versionizers', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('mode');
            $table->string('model_class');
            $table->string('model_id');
            $table->json('data');
            $table->json('changes');
            $table->timestamp('reverted_at')->nullable();
            $table->string('reverted_by')->nullable();
            $table->timestamps();

            $table->index(['mode']);
            $table->index(['model_id']);
            $table->index(['model_class']);
            $table->index(['model_class', 'model_id']);
        });
        if (\File::exists(base_path('vendor/jenssegers/mongodb'))) {
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
    }

    public function down()
    {
        Schema::dropIfExists('versionizers');
    }
};
