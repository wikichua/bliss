<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('key')->index();
            $table->longText('value')->nullable();
            $table->boolean('protected')->default(false);
            $table->timestamps();
            $table->string('created_by')->nullable()->default(1);
            $table->string('updated_by')->nullable()->default(1);
        });
    }

    public function down()
    {
        // drop table
        Schema::dropIfExists('settings');
    }
};
