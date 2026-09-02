<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeToDancePairNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return [
            'mail',
            'database',
        ];
    }


    public function toMail(object $notifiable): MailMessage
    {
        $dashboardRoute =
            $notifiable->role === 'teacher'
                ? route('teacher.dashboard')
                : route('student.dashboard');


        return (new MailMessage)
            ->subject('Welcome to DancePair 🎉')
            ->greeting(
                'Welcome to DancePair, ' .
                $notifiable->name .
                '!'
            )
            ->line(
                'Your DancePair account has been created successfully.'
            )
            ->line(
                $notifiable->role === 'teacher'
                    ? 'You can now complete your teacher profile, add your dance styles, set your rates and publish your availability.'
                    : 'You can now explore dance teachers, send lesson requests and manage your bookings.'
            )
            ->action(
                'Go to My Dashboard',
                $dashboardRoute
            )
            ->line(
                'We’re happy to have you with us.'
            )
            ->salutation(
                'DancePair Team'
            );
    }


    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'welcome',

            'title' => 'Welcome to DancePair',

            'message' =>
                $notifiable->role === 'teacher'
                    ? 'Your teacher account is ready. Complete your profile and start offering lessons.'
                    : 'Your student account is ready. Start discovering dance teachers.',

            'role' => $notifiable->role,

            'url' =>
                $notifiable->role === 'teacher'
                    ? route('teacher.dashboard')
                    : route('student.dashboard'),
        ];
    }
}