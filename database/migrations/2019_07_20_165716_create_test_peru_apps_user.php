<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestPeruAppsUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->charset = 'latin1';
            $table->collation  = 'latin1_spanish_ci';
            
            $table->bigIncrements('id')->autoIncrement();
            $table->string('user_name')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->binary('profile_image')->nullable();
            $table->text('password');
            $table->string('email')->unique();
            $table->ipAddress('visitor');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['first_name', 'last_name']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}

