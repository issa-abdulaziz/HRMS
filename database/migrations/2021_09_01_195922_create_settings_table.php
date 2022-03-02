<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('weekend');
            $table->double('normal_overtime_rate', 5, 3);
            $table->double('weekend_overtime_rate', 5, 3);
            $table->double('leeway_discount_rate', 5, 3);
            $table->double('vacation_rate', 5, 3);
            $table->integer('taking_vacation_allowed_after');
            $table->string('currency');
        });
        
        // Inserting default data
        DB::table('settings')->insert(
            array(
                'currency' => 'USD',
                'weekend' => 'Friday',
                'normal_overtime_rate' => 1.5,
                'weekend_overtime_rate' => 2,
                'leeway_discount_rate' => 1.5,
                'vacation_rate' => 1.25,
                'taking_vacation_allowed_after' => 3,
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
        Schema::dropIfExists('settings');
    }
}
