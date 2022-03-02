<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public $timestamps = false;
    
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public static function getTotalLeewayAmount($month) {
        $employees = Employee::where('active',1)->get();
        $totalLeewayAmount = $employees->map(function($employee, $key) use ($month) {
            return $employee->getLeewayDiscount($month);
        });
        return $totalLeewayAmount->sum();
    }
    public static function getTotalAbsenceAmount($month) {
        $employees = Employee::where('active',1)->get();
        $totalAbsenceAmount = $employees->map(function($employee, $key) use ($month) {
            return $employee->getAbsentDayDiscountAmount($month);
        });
        return $totalAbsenceAmount->sum();
    }
}
