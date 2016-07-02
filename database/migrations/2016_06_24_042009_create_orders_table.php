<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lunchbox_id');
            $table->integer('name');
            $table->float('expected_cost');
            $table->float('actual_cost')->default(0.00);
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('lunchbox_id')
                ->references('id')->on('lunchboxes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
