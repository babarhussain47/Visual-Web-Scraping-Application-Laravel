<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Packages extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('packages', function (Blueprint $table) {
			
			//everything encrypted
			
            $table->integer('p_id')->autoIncrement(); 
            $table->string('p_name')->default('Free'); // 255 max 
            $table->boolean('p_auto_schedule')->default(FALSE); // number
            $table->integer('p_allowed_request')->default(300); // number
            $table->boolean('p_allowed_api')->default(FALSE); // bolean
            $table->integer('p_allowed_api_request')->default(0); // number
            $table->integer('p_allowed_extractors')->default(1); // number
            $table->integer('p_allowed_column')->default(3); // number
            $table->integer('p_allowed_row')->default(100); // number
            $table->float('p_price')->default(0.0); // float 
            $table->string('p_currency',5)->default("USD"); // 5 characters max
            $table->integer('p_validity')->default(30); // in days
            $table->boolean('p_post_data')->default(FALSE); // send the data to the website
            $table->boolean('p_email_data')->default(FALSE); // send the data to the email addresss
            $table->timestamps();
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
