<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {
    }

    public function via(object $notifiable): array
    {
        return [
            'mail',
            'database',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $studentName =
            $this->booking->student?->user?->name
            ?? 'A student';

        $danceStyle =
            $this->booking->danceStyle?->name
            ?? 'Dance Lesson';

        return (new MailMessage)
            ->subject('New lesson request on DancePair')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line(
                $studentName .
                ' sent you a new lesson request.'
            )
            ->line(
                'Dance: ' . $danceStyle
            )
            ->line(
                'Date: ' .
                \Carbon\Carbon::parse(
                    $this->booking->lesson_date
                )->format('M d, Y')
            )
            ->line(
                'Time: ' .
                \Carbon\Carbon::parse(
                    $this->booking->lesson_time
                )->format('g:i A')
            )
            ->line(
                'Price: $' .
                number_format(
                    (float) $this->booking->price,
                    2
                ) .
                ' CAD'
            )
            ->action(
                'Review Request',
                route('teacher.bookings')
            )
            ->line(
                'Please review the request and accept or refuse it from your DancePair dashboard.'
            )
            ->salutation('DancePair Team');
    }

    public function toArray(object $notifiable): array
    {
        $studentName =
            $this->booking->student?->user?->name
            ?? 'A student';

        $danceStyle =
            $this->booking->danceStyle?->name
            ?? 'Dance Lesson';

        return [
            'type' => 'booking_request',

            'title' => 'New Lesson Request',

            'message' =>
                $studentName .
                ' requested a ' .
                $danceStyle .
                ' lesson.',

            'booking_id' =>
                $this->booking->id,

            'student_name' =>
                $studentName,

            'dance_style' =>
                $danceStyle,

            'lesson_date' =>
                $this->booking->lesson_date,

            'lesson_time' =>
                $this->booking->lesson_time,

            'price' =>
                (float) $this->booking->price,

            'url' =>
                route('teacher.bookings'),
        ];
    }
}