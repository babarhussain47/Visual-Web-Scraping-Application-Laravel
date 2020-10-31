<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Extractors extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('extractors', function (Blueprint $table) {
            $table->integer('ext_id')->autoIncrement(); 
            $table->integer('user_id')->foreign()
									->references('id')->on('users')
									->onDelete('cascade');// OK 
            $table->json('ext_data');// OK 
            $table->string('ext_url')->default('');// OK 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('extractors');
    }
}
