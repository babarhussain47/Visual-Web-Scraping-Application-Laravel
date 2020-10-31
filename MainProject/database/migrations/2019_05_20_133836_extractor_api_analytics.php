<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtractorApiAnalytics extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/*Schema::create('extractor_api_analytics', function (Blueprint $table) {
            $table->integer('ext_id')->foreign()
									->references('ext_id')->on('extractors')
									->onDelete('cascade');// OK 
            $table->integer('total_requests')->default(0);// OK 
            $table->boolean('run_method')->default(0);// OK 
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('extractor_api_analytics');
    }
}
