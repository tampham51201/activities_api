<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_activities', function (Blueprint $table) {
            $table->id_activity();
            $table->masv();
            $table->approver();

            // $table->string('title');
            // $table->longText('description')->nullable();
            // $table->tinyInteger('quantity');
            // $table->tinyInteger('point');
            // $table->tinyInteger('registered_quantity');

            // $table->dateTime('time_start_activity');
            // $table->dateTime('time_end_activity');

            // $table->dateTime('time_start_register');
            // $table->dateTime('time_end_register');

            $table->tinyInteger('status')->default('0');
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
        Schema::dropIfExists('register_activities');
    }
}