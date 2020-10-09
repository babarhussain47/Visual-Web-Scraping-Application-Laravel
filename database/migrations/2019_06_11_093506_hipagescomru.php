<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Hipagescomru extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('hipagescomru', function (Blueprint $table) {
            $table->increments('id');
            $table->string('siteName',100);
            $table->string('serviceArea',100);
            $table->string('contactNo',30);
            $table->string('last_checked_at',10)->default('');
            $table->string('removed_at',10)->default('');
            $table->string('created_at',10)->default('');//30-12-2011
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
