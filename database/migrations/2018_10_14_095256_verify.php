<?php
/*
* Checked and Synchronized on Sunday, 14 October 2018
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Verify extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('verify', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); 
            $table->string('phone',20)->default("");
            $table->string('code',10)->default("");
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
         Schema::dropIfExists('verify');
    }
}
