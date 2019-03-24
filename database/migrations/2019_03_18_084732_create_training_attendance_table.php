<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_attendance', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('training_id');
            $table->date('training_attendance_date');
            $table->uuid('training_attendance_time_id');
            $table->boolean('training_attendance_status')->default(false);
            $table->timestamps();
            $table->primary('id');
            $table->foreign('training_id')->references('id')->on('training');
            $table->foreign('training_attendance_time_id')->references('id')->on('available_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_attendance');
    }
}
