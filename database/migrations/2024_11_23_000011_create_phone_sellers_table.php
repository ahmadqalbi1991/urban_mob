<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('phone', 255)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('dial_code', 255)
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
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn(['phone', 'dial_code']); 
        });
    }
}
