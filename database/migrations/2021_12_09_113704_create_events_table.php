<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('location');
            $table->string('meeting_point');
            // TODO: think of what to do with status
            $table->foreignId('status_id');
            $table->dateTime('starting_time');
            $table->dateTime('ending_time');
            $table->string('preview')->default('default.jpg');
            $table->integer('attendees_limit');
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('event_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
