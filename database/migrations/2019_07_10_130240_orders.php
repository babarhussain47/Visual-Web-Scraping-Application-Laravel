<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Orders extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('orders', function (Blueprint $table) {
            $table->integer('o_id')->autoIncrement();
			$table->integer('t_id')->foreign()
									->references('t_id')->on('transactions')
									->onDelete('cascade');
			$table->integer('user_id')->foreign()
									->references('id')->on('users')
									->onDelete('cascade');
            $table->integer('p_id')->foreign()// OK
									->references('package_id')->on('packages')
									->onDelete('cascade'); 
            $table->boolean('order_status')->default(FALSE);
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
         Schema::dropIfExists('orders');
    }
}
