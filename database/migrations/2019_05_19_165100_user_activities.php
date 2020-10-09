<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserActivities extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('user_activities', function (Blueprint $table) {
            $table->integer('user_id')->foreign()
									->references('id')->on('users')
									->onDelete('cascade');// OK 
            $table->boolean('act_type')->default(FALSE);// OK 
            $table->string('act_desc')->default('');// OK 
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
         Schema::dropIfExists('user_activities');
    }
}
