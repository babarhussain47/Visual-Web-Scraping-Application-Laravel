<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ErrorCodes extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('error_codes', function (Blueprint $table) {
            $table->integer('error_id')->autoIncrement(); 
            $table->string('error_code')->default("100"); 
            $table->string('error_message_default')->default("100");
            $table->string('error_message_custom')->default("100");
            $table->string('error_type')->default("100");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('error_codes');
    }
}
