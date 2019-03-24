<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingAvailableTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_available_time', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('training_id');
            $table->uuid('available_time_id');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
            $table->foreign('training_id')->references('id')->on('training')->onDelete('cascade');
            $table->foreign('available_time_id')->references('id')->on('available_time')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_available_time');
    }
}
