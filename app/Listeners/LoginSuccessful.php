<?php

namespace App\Listeners;

use App\models\Setting;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \IlluminateAuthEventsLogin  $event
     * @return void
     */
    public function handle($event)
    {
        Setting::firstOrCreate([
            'user_id' => auth   ()->id(),
        ],[
            'currency' => 'USD',
            'weekend' => 'Friday',
            'normal_overtime_rate' => 1.5,
            'weekend_overtime_rate' => 2,
            'leeway_discount_rate' => 1.5,
            'vacation_rate' => 1.25,
            'taking_vacation_allowed_after' => 3,
        ]);
        session(['setting' => Setting::whereBelongsTo(auth()->user())->first()]);
    }
}
