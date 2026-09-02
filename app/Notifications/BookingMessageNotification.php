<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\BookingMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public BookingMessage $bookingMessage,
        public string $senderName
    ) {
    }

    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isTeacher = $notifiable->role === 'teacher';

        $url = $isTeacher
            ? route('teacher.bookings')
            : route('student.bookings');

        return (new MailMessage)
            ->subject('New DancePair message')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line(
                $this->senderName .
                ' sent you a new message about a dance lesson.'
            )
            ->line('"' . $this->bookingMessage->message . '"')
            ->action('View Message', $url)
            ->line('You can reply from your DancePair account.');
    }

    public function toArray(object $notifiable): array
    {
        $isTeacher = $notifiable->role === 'teacher';

        return [
            'type' => 'booking_message',

            'title' => 'New Message',

            'message' =>
                $this->senderName .
                ' sent you a message about your dance lesson.',

            'booking_id' => $this->booking->id,

            'booking_message_id' => $this->bookingMessage->id,

            'sender_name' => $this->senderName,

            'url' => $isTeacher
                ? route('teacher.bookings')
                : route('student.bookings'),
        ];
    }
}