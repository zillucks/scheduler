<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('menu', function (Blueprint $table) {
			$table->uuid('id');
			$table->uuid('parent_id')->nullable();
            $table->string('label', 50)->nullable();
			$table->string('urls', 100);
			$table->smallInteger('order_priority');
            $table->boolean('menu_status')->default(true);
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
		Schema::dropIfExists('menu');
	}
}
