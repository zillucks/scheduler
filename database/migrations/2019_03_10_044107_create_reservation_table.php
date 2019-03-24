<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('training_id');
            $table->uuid('user_identity_id');
            $table->enum('reservation_type', ['mandiri', 'group'])->default('mandiri');
            $table->date('reservation_date');
            $table->uuid('reservation_time');
            $table->string('manager_email')->nullable();
            $table->enum('reservation_status', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->string('user_log')->nullable();
            $table->primary('id');
            $table->foreign('training_id')->references('id')->on('training');
            $table->foreign('user_identity_id')->references('id')->on('identity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation');
    }
}
