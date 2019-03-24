<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('site_id');
            $table->uuid('class_id');
            $table->string('training_name', 50);
            $table->string('slug', 50)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('training_status')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->string('user_log');
            $table->primary('id');
            $table->foreign('site_id')->references('id')->on('site');
            $table->foreign('class_id')->references('id')->on('class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training');
    }
}
