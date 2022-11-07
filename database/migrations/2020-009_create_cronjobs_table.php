<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cronjobs', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('name')->nullable()->default('');
            $table->string('mode', 4)->nullable()->default('art'); // [art/exec]
            $table->string('timezone')->nullable()->default('UTC');
            $table->string('command')->nullable();
            $table->string('frequency')->nullable()->default('everyMinute');
            $table->json('output')->nullable();
            $table->string('status', 1)->nullable()->default('I');
            $table->string('created_by')->nullable()->default(1);
            $table->string('updated_by')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cronjobs');
    }
};
