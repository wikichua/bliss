<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cronjobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default('');
            $table->string('mode', 4)->nullable()->default('art'); // [art/exec]
            $table->string('timezone')->nullable()->default('UTC');
            $table->string('command')->nullable();
            $table->string('frequency')->nullable()->default('everyMinute');
            $table->json('output')->nullable();
            $table->string('status', 1)->nullable()->default('I');
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cronjobs');
    }
};
