<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class MobileVerifyEmailNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl =
            URL::temporarySignedRoute(
                'api.v1.email.verify',

                now()->addMinutes(
                    config(
                        'auth.verification.expire',
                        60
                    )
                ),

                [
                    'id' =>
                        $notifiable->getKey(),

                    'hash' =>
                        sha1(
                            $notifiable
                                ->getEmailForVerification()
                        ),
                ]
            );


        return (new MailMessage)
            ->subject(
                'Verify your DancePair email'
            )
            ->greeting(
                'Welcome to DancePair!'
            )
            ->line(
                'Please verify your email address to activate your DancePair account.'
            )
            ->action(
                'Verify Email',
                $verificationUrl
            )
            ->line(
                'If you did not create this account, you can ignore this email.'
            );
    }
}