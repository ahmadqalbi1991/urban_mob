<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('reward_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();  
            $table->integer('value');          
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reward_configs');
    }
}