<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->time('starting_time');
            $table->time('leaving_time');
            $table->boolean('across_midnight');
        });

        // Inserting default data
        DB::table('shifts')->insert(
            array(
                'title' => 'Morning Shift',
                'starting_time' => date("H:i:s", strtotime('7:00 AM')),
                'leaving_time' => date("H:i:s", strtotime('3:00 PM')),
                'across_midnight' => false,
            )
        );
        DB::table('shifts')->insert(
            array(
                'title' => 'Evening Shift',
                'starting_time' => date("H:i:s", strtotime('3:00 PM')),
                'leaving_time' => date("H:i:s", strtotime('12:00 AM')),
                'across_midnight' => true,
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
