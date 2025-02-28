<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departure_id'); // Departure airport
            $table->unsignedBigInteger('arrival_id');   // Arrival airport

            $table->foreign('departure_id')->references('id')->on('airports')->onDelete('cascade');
            $table->foreign('arrival_id')->references('id')->on('airports')->onDelete('cascade');
            
            $table->string('departure_code');
            $table->string('arrival_code');
            $table->date('departure_date');
            $table->date('arrival_date');
            $table->time('departure_time');
            $table->time('arrival_time');
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
        Schema::dropIfExists('flights');
    }
};
