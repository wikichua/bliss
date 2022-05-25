<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default('')->unique();
            $table->json('queries')->nullable();
            $table->string('status', 1)->nullable()->default('');
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            $table->integer('cache_ttl')->nullable()->default(300); // 5 mins
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('next_generate_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
