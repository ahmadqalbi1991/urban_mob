<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddDeviceColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_device_token', 255)
                  ->nullable()
                  ->default('0')
                  ->collation('utf8mb3_unicode_ci'); 

            $table->string('user_device_type', 255)
                  ->nullable()
                  ->default('0')
                  ->collation('utf8mb3_unicode_ci'); 

            $table->string('device_cart_id', 255)
                  ->nullable()
                  ->default('0')
                  ->collation('utf8mb3_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_device_token', 'user_device_type', 'device_cart_id']); // Remove columns
        });
    }
}
