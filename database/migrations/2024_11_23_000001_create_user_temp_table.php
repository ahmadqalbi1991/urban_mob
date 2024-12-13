<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_temps', function (Blueprint $table) {
            $table->id(); 
            $table->enum('role', ['vendor', 'customer', 'admin', 'super-admin', 'user', 'seller'])
                  ->default('customer')
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('name', 100)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->text('password')
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('email', 250)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('remember_token', 100)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('phone', 100)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci');
            $table->string('city', 100)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->smallInteger('otp')
                  ->nullable(); 
            $table->text('device_token')
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->enum('registered_by', ['Web', 'App', 'App1'])
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci');
            $table->boolean('is_registered')
                  ->default(0); 
            $table->boolean('is_active')
                  ->default(1); 
            $table->boolean('is_verified')
                  ->default(0); 
            $table->timestamp('created_at')
                  ->useCurrent(); 
            $table->dateTime('updated_at')
                  ->nullable();
            $table->dateTime('email_verified_at')
                  ->nullable(); 
            $table->dateTime('last_login')
                  ->nullable(); 
            $table->string('profile', 255)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('gender', 191)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->date('DOB')
                  ->nullable(); 
            $table->enum('verify', ['True', 'False'])
                  ->default('True')
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('wallet_balance', 255)
                  ->default('0')
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('user_device_token', 255)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci');
            $table->string('user_device_type', 255)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('device_cart_id', 255)
                  ->nullable()
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
        Schema::dropIfExists('user_temps');
    }
}
