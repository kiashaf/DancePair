<?php

namespace App\Notifications;

use App\Models\Booking;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRejectedNotification extends Notification
{
    use Queueable;

    protected Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }


    /*
    |--------------------------------------------------------------------------
    | CHANNELS
    |--------------------------------------------------------------------------
    */

    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | DATABASE NOTIFICATION
    |--------------------------------------------------------------------------
    */

    public function toDatabase(
        object $notifiable
    ): array {
        $this->booking->loadMissing([
            'teacher.user',
            'danceStyle',
        ]);

        $teacherName =
            $this->booking
                ->teacher
                ?->user
                ?->name
            ?? 'The teacher';

        $danceName =
            $this->booking
                ->danceStyle
                ?->name
            ?? 'Dance lesson';

        $lessonDate =
            \Carbon\Carbon::parse(
                $this->booking->lesson_date
            )->format(
                'M d, Y'
            );

        $lessonTime =
            \Carbon\Carbon::parse(
                $this->booking->lesson_time
            )->format(
                'g:i A'
            );

        return [

            'type' =>
                'booking_rejected',

            'title' =>
                'Lesson Request Not Accepted',

            'message' =>
                $teacherName
                . ' was unable to accept your '
                . $danceName
                . ' lesson request for '
                . $lessonDate
                . ' at '
                . $lessonTime
                . '.',

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

            'url' =>
                route(
                    'student.bookings'
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | EMAIL
    |--------------------------------------------------------------------------
    */

    public function toMail(
        object $notifiable
    ): MailMessage {
        $this->booking->loadMissing([
            'teacher.user',
            'danceStyle',
        ]);

        $teacherName =
            $this->booking
                ->teacher
                ?->user
                ?->name
            ?? 'The teacher';

        $danceName =
            $this->booking
                ->danceStyle
                ?->name
            ?? 'Dance lesson';

        $lessonDate =
            \Carbon\Carbon::parse(
                $this->booking->lesson_date
            )->format(
                'M d, Y'
            );

        $startTime =
            \Carbon\Carbon::parse(
                $this->booking->lesson_time
            );

        $endTime =
            $startTime
                ->copy()
                ->addMinutes(
                    $this->booking->duration ?? 60
                );

        return (new MailMessage)

            ->subject(
                'Your DancePair lesson request was not accepted'
            )

            ->greeting(
                'Hi '
                . ($notifiable->name ?? '')
            )

            ->line(
                $teacherName
                . ' was unable to accept your lesson request.'
            )

            ->line(
                'Dance: '
                . $danceName
            )

            ->line(
                'Date: '
                . $lessonDate
            )

            ->line(
                'Time: '
                . $startTime->format('g:i A')
                . ' - '
                . $endTime->format('g:i A')
            )

            ->line(
                'You can choose another available time or find another teacher on DancePair.'
            )

            ->action(
                'Find a Teacher',
                route(
                    'student.teachers'
                )
            )

            ->line(
                'Thank you for using DancePair.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ARRAY
    |--------------------------------------------------------------------------
    */

    public function toArray(
        object $notifiable
    ): array {
        return $this->toDatabase(
            $notifiable
        );
    }
}