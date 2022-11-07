<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('name')->index();
            $table->boolean('admin')->default(false)->index();
            $table->timestamps();
            $table->string('created_by')->nullable()->default(1);
            $table->string('updated_by')->nullable()->default(1);
        });

        // create-role user relation table
        Schema::create('role_user', function (Blueprint $table) {
            // $table->ulid('id');
            $table->string('role_id')->index();
            $table->string('user_id')->index();
        });
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }
};
