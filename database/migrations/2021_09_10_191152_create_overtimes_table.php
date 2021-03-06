<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('time'); // in minutes
            $table->double('rate', 5, 3);
            $table->integer('salary'); // salary of the employee at that date
            $table->double('working_hour', 5, 3); // this is needed for calculating the hourrly price
            $table->integer('amount');
            $table->string('note');
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtimes');
    }
}
