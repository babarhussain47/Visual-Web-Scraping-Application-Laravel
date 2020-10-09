<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Apps extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('apps', function (Blueprint $table) {
            $table->increments('app_id');// OK 
            $table->string('app_name',30);// OK 
            $table->string('app_website',100);// OK 
            $table->integer('ext_id');// OK 
            $table->integer('user_id');// OK 
            $table->string('public_key',70);// OK 
            $table->string('private_key',70);// OK 
            $table->boolean('active')->default(true);// OK 
            $table->timestamps();// OK 
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
