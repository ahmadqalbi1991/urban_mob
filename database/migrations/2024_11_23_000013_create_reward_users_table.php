<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardUsersTable extends Migration
{
    public function up()
    {
        Schema::create('reward_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reward_config_id')->constrained('reward_configs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('amounts');  
            $table->integer('points');  
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reward_users');
    }
}
