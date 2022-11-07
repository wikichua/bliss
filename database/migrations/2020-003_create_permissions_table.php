<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('group');
            $table->string('name');
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });

        // create-permission role relation table
        Schema::create('permission_role', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('permission_id')->index();
            $table->string('role_id')->index();
        });

        // create-permission user relation table
        Schema::create('permission_user', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('permission_id')->index();
            $table->string('user_id')->index();
        });
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permission_user');
    }
};
