<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_user', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('reservation_id');
            $table->uuid('user_identity_id');
            $table->enum('reservation_user_as', ['registrar', 'member'])->default('registrar');
            $table->enum('reservation_user_status', ['awaiting', 'present', 'absent'])->default('awaiting');
            $table->timestamps();
            $table->primary('id');
            $table->foreign('reservation_id')->references('id')->on('reservation')->onDelete('cascade');
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
        Schema::dropIfExists('reservation_user');
    }
}
