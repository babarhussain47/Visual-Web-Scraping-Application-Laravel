<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageSubscribed extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('subscriptions', function (Blueprint $table) {
			
			//everything encrypted
			
            $table->integer('s_id')->autoIncrement(); 
            $table->integer('sp_id'); 
            $table->integer('u_id'); 
            $table->integer('p_allowed_request_rem')->default(300); // number
            $table->integer('p_allowed_api_request_rem')->default(0); // number
			
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
        Schema::dropIfExists('subscriptions');
    }
}
