<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExtractors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extractors', function (Blueprint $table) {
		   $table->string('post_url')->nullable();
		   $table->string('post_url_en',2)->default('');
		   $table->string('ext_run_type',9)->default('no_repeat');
		   $table->string('ext_time',5)->default(''); 	// 02:26
		   $table->string('ext_day',3)->default('');		// 00-31
		   $table->string('ext_date',2)->default(''); 	// 20-12-2018
		   $table->string('ext_month',2)->default('');	// 00-12
	   });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
