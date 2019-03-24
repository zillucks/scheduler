<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingAttendanceUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_attendance_user', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('training_attendance_id');
            $table->uuid('reservation_id');
            $table->uuid('identity_id');
            $table->enum('training_attendance_user_status', ['awaiting', 'present', 'absent'])->default('awaiting');
            $table->timestamps();
            $table->string('user_log')->nullable();
            $table->primary('id');
            $table->foreign('training_attendance_id')->references('id')->on('training_attendance');
            $table->foreign('reservation_id')->references('id')->on('reservation')->onDelete('cascade');
            $table->foreign('identity_id')->references('id')->on('identity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_attendance_user');
    }
}
