<?php

namespace App\Models;

use App\models\AdvancedPayment;
use App\models\Employee;
use App\models\Overtime;
use App\models\Setting;
use App\models\Shift;
use App\models\Vacation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function setting()
    {
        return $this->hasOne(Setting::class);
    }
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
    public function advancedPayments()
    {
        return $this->hasManyThrough(AdvancedPayment::class, Employee::class);
    }
    public function overtimes()
    {
        return $this->hasManyThrough(Overtime::class, Employee::class);
    }
    public function vacations()
    {
        return $this->hasManyThrough(Vacation::class, Employee::class);
    }
}
