<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->ulid('id')->first();
            $table->string('avatar')->nullable();
            $table->string('timezone')->default(config('app.timezone'))->index();
            $table->string('status', 1)->nullable()->default('I');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });

        Schema::dropIfExists('password_resets');
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        if (Schema::hasTable('personal_access_tokens')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                if (config('database.default') != 'sqlite') {
                    $table->dropColumn('id');
                }
            });
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                if (config('database.default') != 'sqlite') {
                    $table->increments('id')->before('tokenable');
                }
                $table->string('plain_text_token')->nullable()->index();
            });
        }
    }

    public function down()
    {
        // drop columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('timezone');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        Schema::drop('password_resets');
    }
};
