<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transactions extends Migration
{
 
	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('transactions', function (Blueprint $table) {
			$table->integer('t_id')->autoIncrement(); // OK 
            $table->integer('t_Amount')->default(0);
            $table->string('t_AuthCode',12)->default("");
            $table->string('t_BankID',30)->default("");
            $table->string('t_Language',5)->default("");
            $table->string('t_ResponseCode',5)->default("");
            $table->string('t_ResponseMessage',200)->default("");
            $table->string('t_RetreivalReferenceNo',12)->default("");
            $table->string('t_SettlementExpiry',20)->default("");
            $table->string('t_TxnType',10)->default("");
            $table->string('t_SubMerchantId',10)->default("");
            $table->string('t_TxnCurrency',5)->default("");
            $table->string('t_TxnDateTime',14)->default("");
            $table->string('t_TxnRefNo',18)->default("");
            $table->boolean('transaction_status')->default(FALSE);
            $table->integer('p_id')->foreign()// OK
									->references('p_id')->on('packages')
									->onDelete('cascade');
            $table->integer('o_id')->foreign()// OK
									->references('o_id')->on('orders')
									->onDelete('cascade'); 
            $table->integer('user_id')->foreign()// OK
									->references('id')->on('users')
									->onDelete('cascade'); 
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
         Schema::dropIfExists('transactions');
    }
}
