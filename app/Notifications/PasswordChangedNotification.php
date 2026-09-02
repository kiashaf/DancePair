<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return [
            'mail',
            'database',
        ];
    }


    private function dashboardUrl(object $notifiable): string
    {
        return match ($notifiable->role) {

            'student' => route('student.dashboard'),

            'teacher' => route('teacher.dashboard'),

            'admin' => route('admin.dashboard'),

            default => url('/'),
        };
    }


    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your DancePair password was changed')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line(
                'The password for your DancePair account was changed successfully.'
            )
            ->line(
                'If you made this change, no further action is required.'
            )
            ->line(
                'If you did not change your password, please contact DancePair support immediately.'
            )
            ->action(
                'Go to Dashboard',
                $this->dashboardUrl($notifiable)
            )
            ->salutation('DancePair Team');
    }


    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'password_changed',

            'title' => 'Password Changed',

            'message' =>
                'Your DancePair account password was changed successfully.',

            'url' =>
                $this->dashboardUrl($notifiable),
        ];
    }
}