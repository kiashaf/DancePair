<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingAcceptedNotification extends Notification
{
    use Queueable;

    protected Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        $this->booking->loadMissing([
            'teacher.user',
            'danceStyle',
        ]);

        $teacherName =
            $this->booking->teacher?->user?->name
            ?? 'Your teacher';

        $danceName =
            $this->booking->danceStyle?->name
            ?? 'Dance lesson';

        $lessonDate = \Carbon\Carbon::parse(
            $this->booking->lesson_date
        )->format('M d, Y');

        $lessonTime = \Carbon\Carbon::parse(
            $this->booking->lesson_time
        )->format('g:i A');

        return [
            'type' => 'booking_accepted',

            'title' => 'Lesson Request Accepted',

            'message' =>
                $teacherName
                . ' accepted your '
                . $danceName
                . ' lesson request for '
                . $lessonDate
                . ' at '
                . $lessonTime
                . '. Please complete your payment.',

            'booking_id' =>
                $this->booking->id,

            'teacher_id' =>
                $this->booking->teacher_id,

            'dance_style_id' =>
                $this->booking->dance_style_id,

            'lesson_date' =>
                $lessonDate,

            'lesson_time' =>
                $lessonTime,

            'price' =>
                (float) $this->booking->price,

            'url' =>
                route(
                    'student.bookings'
                ),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->booking->loadMissing([
            'teacher.user',
            'danceStyle',
        ]);

        $teacherName =
            $this->booking->teacher?->user?->name
            ?? 'Your teacher';

        $danceName =
            $this->booking->danceStyle?->name
            ?? 'Dance lesson';

        $lessonDate = \Carbon\Carbon::parse(
            $this->booking->lesson_date
        )->format('M d, Y');

        $startTime = \Carbon\Carbon::parse(
            $this->booking->lesson_time
        );

        $endTime = $startTime
            ->copy()
            ->addMinutes(
                $this->booking->duration ?? 60
            );

        return (new MailMessage)
            ->subject(
                'Your DancePair lesson request was accepted'
            )

            ->greeting(
                'Hi ' . ($notifiable->name ?? '')
            )

            ->line(
                $teacherName
                . ' accepted your lesson request.'
            )

            ->line(
                'Dance: ' . $danceName
            )

            ->line(
                'Date: ' . $lessonDate
            )

            ->line(
                'Time: '
                . $startTime->format('g:i A')
                . ' - '
                . $endTime->format('g:i A')
            )

            ->line(
                'Amount: $'
                . number_format(
                    (float) $this->booking->price,
                    2
                )
                . ' CAD'
            )

            ->line(
                'Please complete your payment to secure your lesson.'
            )

            ->action(
                'Pay Now',
                route(
                    'student.payments.show',
                    $this->booking
                )
            )

            ->line(
                'Thank you for using DancePair.'
            );
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase(
            $notifiable
        );
    }
}