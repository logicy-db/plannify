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
            $table->foreignId('event_status_id')->constrained()->cascadeOnDelete();
            $table->dateTime('starting_time');
            $table->dateTime('ending_time');
            $table->string('preview')->default('default.jpg');
            $table->integer('attendees_limit');
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
        Schema::dropIfExists('events');
    }
}
