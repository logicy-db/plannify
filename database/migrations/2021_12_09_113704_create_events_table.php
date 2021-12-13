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
            $table->text('name');
            $table->text('description');
            $table->text('location');
            // TODO: think of what to do with status
            $table->smallInteger('status');
            $table->date('starting_date');
            $table->time('starting_time');
            // TODO: add preview and event images
            $table->text('image');
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
