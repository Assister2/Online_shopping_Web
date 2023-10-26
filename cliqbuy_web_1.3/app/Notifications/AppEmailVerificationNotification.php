<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use App\Mail\EmailManager;
use Auth;
use App\User;

class AppEmailVerificationNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $array['view'] = 'emails.app_verification';
        $array['subject'] = trans('messages.front_end.email_verification');
        $array['content'] = trans('messages.front_end.please_enter_code').$notifiable->verification_code;
         logger('Email OTP : '.trans('messages.front_end.please_enter_code').$notifiable->verification_code);

        return (new MailMessage)
            ->view('emails.app_verification', ['array' => $array])
            ->subject(trans('messages.front_end.ev').env('APP_NAME'));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
