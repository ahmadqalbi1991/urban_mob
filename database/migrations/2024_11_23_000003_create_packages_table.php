<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('packages', function (Blueprint $table) {
            $table->id(); 
            $table->string('name', 100)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->string('amount', 100)
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci');
            $table->text('description')
                  ->nullable()
                  ->collation('utf8mb3_unicode_ci'); 
            $table->foreignId('service_id') 
                  ->constrained('services')
                  ->onDelete('cascade'); 
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
        Schema::dropIfExists('packages');
    }
}
