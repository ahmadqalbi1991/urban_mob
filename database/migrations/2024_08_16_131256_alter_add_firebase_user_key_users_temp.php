<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddFirebaseUserKeyUsersTemp extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_temps', function (Blueprint $table) {
            $table->string("firebase_user_key")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_temps', function (Blueprint $table) {
            $table->dropColumn("firebase_user_key");
        });
    }
};
