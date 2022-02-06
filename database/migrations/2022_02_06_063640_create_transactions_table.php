<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->double('sent_amount');
            $table->double('received_amount');
            $table->enum('sent_currency', ['USD', 'EUR']);
            $table->enum('received_currency', ['USD', 'EUR']);
            $table->string('converted_from')->nullable();
            $table->json('convertion_response')->nullable();
            $table->double('convertion_rate')->nullable();
            $table->enum('stauts', ['pending', 'sent', 'failed', 'rejected', 'cancel']);
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
