<?php

namespace App\Broadcasting;

use Ghasedak\Laravel\GhasedakFacade;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        return 'Done!';

        // $receptor = $notifiable->cellphone;
        // $type = 1;
        // $template = "otp";
        // $param1 = $notification->code;
        // $response = Ghasedak\Laravel\GhasedakFacade::setVerifyType($type)->Verify($receptor, $template, $param1);
    }
}
