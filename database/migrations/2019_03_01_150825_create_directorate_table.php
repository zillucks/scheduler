<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectorateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directorate', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('directorate_name', 50);
            $table->string('slug', 50)->unique();
            $table->boolean('directorate_status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->string('user_log');
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directorate');
    }
}
