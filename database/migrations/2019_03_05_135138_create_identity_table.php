<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identity', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('full_name', 100);
            $table->string('email', 50)->unique()->nullable();
            $table->uuid('site_id')->nullable();
            $table->uuid('directorate_id')->nullable();
            $table->uuid('organization_id')->nullable();
            $table->uuid('department_id')->nullable();
            $table->uuid('unit_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->boolean('identity_status')->default(true);
            $table->boolean('user_ldap')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
            $table->string('user_log')->nullable();
            $table->foreign('site_id')->references('id')->on('site');
            $table->foreign('directorate_id')->references('id')->on('directorate');
            $table->foreign('organization_id')->references('id')->on('organization');
            $table->foreign('department_id')->references('id')->on('department');
            $table->foreign('unit_id')->references('id')->on('unit');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('identity');
    }
}
