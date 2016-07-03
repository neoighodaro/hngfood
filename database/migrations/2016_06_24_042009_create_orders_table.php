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
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('lunch_id');
            $table->unsignedInteger('lunchbox_id');
            $table->string('name');
            $table->float('cost')->default(0.00);
            $table->float('cost_variation')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('lunch_id')
                ->references('id')->on('lunches')
                ->onDelete('cascade');

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
