<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->string('email', 60);
            $table->string('password');
            $table->string('first_name', 36);
            $table->string('last_name', 36);
            $table->boolean('is_active')->default(false);
            $table->rememberToken();
            $table->string('confirmation_token', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['email', 'is_active', 'deleted_at']);
            $table->unique(['email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
