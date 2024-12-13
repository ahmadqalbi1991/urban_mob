<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefferedDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preffered_days', function (Blueprint $table) {
            $table->id(); 
            $table->string('name', 100)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->timestamp('created_at')
                  ->useCurrent(); 
            $table->dateTime('updated_at')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preffered_days');
    }
}
