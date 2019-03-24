<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('site_id');
            $table->string('class_name', 50);
            $table->string('slug', 50)->unique();
            $table->integer('max_quotes')->unsigned();
            $table->boolean('class_status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->string('user_log');
            $table->primary('id');
            $table->foreign('site_id')->references('id')->on('site');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class');
    }
}
