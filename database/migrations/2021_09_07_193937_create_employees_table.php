<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('city');
            $table->string('phone_number');
            $table->date('hired_at');
            $table->string('position');
            $table->integer('salary');
            $table->boolean('active');
            $table->date('vacation_start_count_at')->nullable();
            $table->integer('taken_vacations_days');
            $table->unsignedInteger('shift_id');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');

        });
        // Inserting default data
        DB::table('employees')->insert(
            array(
                'full_name' => 'Issa Abdulaziz',
                'date_of_birth' => date("y/m/d", strtotime('1998/12/9')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '1000',
                'active' => true,
                'shift_id' => 1,
                'vacation_start_count_at' => date("y/m/d", strtotime('2021/5/1')),
                'taken_vacations_days' => '0',
            )
        );
        DB::table('employees')->insert(
            array(
                'full_name' => 'Ibrahim Abdulaziz',
                'date_of_birth' => date("y/m/d", strtotime('1998/11/1')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '1000',
                'active' => true,
                'shift_id' => 2,
                'vacation_start_count_at' => date("y/m/d", strtotime('2021/9/1')),
                'taken_vacations_days' => '0',
            )
        );
        DB::table('employees')->insert(
            array(
                'full_name' => 'Alaa Abdulaziz',
                'date_of_birth' => date("y/m/d", strtotime('1998/10/1')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '800',
                'active' => true,
                'shift_id' => 2,
                'vacation_start_count_at' => date("y/m/d", strtotime('2021/6/1')),
                'taken_vacations_days' => '0',
            )
        );
        DB::table('employees')->insert(
            array(
                'full_name' => 'Issam Alsafadi',
                'date_of_birth' => date("y/m/d", strtotime('1998/10/1')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '1200',
                'active' => true,
                'shift_id' => 1,
                'vacation_start_count_at' => date("y/m/d", strtotime('2021/6/1')),
                'taken_vacations_days' => '0',
            )
        );
        DB::table('employees')->insert(
            array(
                'full_name' => 'Mohammad Abdulaziz',
                'date_of_birth' => date("y/m/d", strtotime('1998/12/9')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '800',
                'active' => true,
                'shift_id' => 1,
                'taken_vacations_days' => '0',
            )
        );
        DB::table('employees')->insert(
            array(
                'full_name' => 'Morad Almdar',
                'date_of_birth' => date("y/m/d", strtotime('1998/11/5')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '1200',
                'active' => true,
                'shift_id' => 1,
                'vacation_start_count_at' => date("y/m/d", strtotime('2021/9/1')),
                'taken_vacations_days' => '0',
            )
        );
        DB::table('employees')->insert(
            array(
                'full_name' => 'Adam Alsafadi',
                'date_of_birth' => date("y/m/d", strtotime('1998/10/1')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '1000',
                'active' => true,
                'shift_id' => 1,
                'vacation_start_count_at' => date("y/m/d", strtotime('2021/7/1')),
                'taken_vacations_days' => '0',
            )
        );
        DB::table('employees')->insert(
            array(
                'full_name' => 'Ahmad Nour',
                'date_of_birth' => date("y/m/d", strtotime('1998/10/1')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '600',
                'active' => true,
                'shift_id' => 2,
                'vacation_start_count_at' => date("y/m/d", strtotime('2021/6/1')),
                'taken_vacations_days' => '0',
            )
        );
        DB::table('employees')->insert(
            array(
                'full_name' => 'Jinan Barakeh',
                'date_of_birth' => date("y/m/d", strtotime('1998/10/1')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '600',
                'active' => true,
                'shift_id' => 1,
                'vacation_start_count_at' => date("y/m/d", strtotime('2021/6/1')),
                'taken_vacations_days' => '0',
            )
        );
        DB::table('employees')->insert(
            array(
                'full_name' => 'Saleh Ghaly',
                'date_of_birth' => date("y/m/d", strtotime('1998/10/1')),
                'city' => 'Saida',
                'phone_number' => '76713856',
                'hired_at' => date("y/m/d", strtotime('2021/3/1')),
                'position' => 'Laravel Developer',
                'salary' => '600',
                'active' => true,
                'shift_id' => 2,
                'vacation_start_count_at' => date("y/m/d", strtotime('2021/6/1')),
                'taken_vacations_days' => '0',
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
        Schema::dropIfExists('employees');
    }
}
