<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthTokens extends Migration
{
    public function up()
    {
        Schema::create('auth_tokens', function(Blueprint $table) {
            $table->increments('id');
            $table->string('hash');
            $table->json('permissions');
            $table->timestamp('expiresAt')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('auth_tokens');
    }
}
