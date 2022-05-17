<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->id();
            $table->string('weekend');
            $table->double('normal_overtime_rate', 5, 3);
            $table->double('weekend_overtime_rate', 5, 3);
            $table->double('leeway_discount_rate', 5, 3);
            $table->double('vacation_rate', 5, 3);
            $table->integer('taking_vacation_allowed_after');
            $table->string('currency');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
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
