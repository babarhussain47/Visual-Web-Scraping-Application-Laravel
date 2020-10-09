<?php
/*
* Checked and Synchronized on Sunday, 14 October 2018
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VerifyUsers extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('verify_users', function (Blueprint $table) {
            $table->integer('id')->autoIncrement(); 
            $table->integer('user_id')->default(0); 
            $table->string('token',60)->default("");
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
         Schema::dropIfExists('verify_users');
    }
}
