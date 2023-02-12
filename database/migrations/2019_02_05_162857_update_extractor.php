<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExtractor extends Migration
{
/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('extractors', function (Blueprint $table) {
            $table->json('ext_bot');// OK 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('extractors');
    }
}
