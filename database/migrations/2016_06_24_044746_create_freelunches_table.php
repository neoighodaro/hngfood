<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreelunchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelunches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reason');
            $table->string('from_id');
            $table->string('to_id');
            $table->boolean('redeemed')->default(false);
            $table->dateTime('expires_at');
            $table->timestamps();

            $table->foreign('from_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('to_id')
                ->references('id')->on('users')
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
        Schema::drop('freelunches');
    }
}
