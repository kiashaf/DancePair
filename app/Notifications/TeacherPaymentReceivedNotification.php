<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherPaymentReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public Payment $payment
    ) {
    }

    /**
     * Notification channels
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
        ];
    }

    /**
     * Email notification
     */
    public function toMail(object $notifiable): MailMessage
    {
        $studentName =
            $this->booking->student?->user?->name
            ?? 'Student';

        $danceStyle =
            $this->booking->danceStyle?->name
            ?? 'Dance Lesson';

        return (new MailMessage)
            ->subject('Payment Received - DancePair')

            ->greeting(
                'Hi ' . $notifiable->name . '!'
            )

            ->line(
                $studentName .
                ' has completed the payment for ' .
                $danceStyle . '.'
            )

            ->line(
                'Lesson price: $' .
                number_format(
                    (float) $this->payment->amount,
                    2
                ) .
                ' CAD'
            )

            ->line(
                'Platform fee: $' .
                number_format(
                    (float) $this->payment->platform_fee,
                    2
                ) .
                ' CAD'
            )

            ->line(
                'Your earnings: $' .
                number_format(
                    (float) $this->payment->teacher_amount,
                    2
                ) .
                ' CAD'
            )

            ->line(
                'The lesson is now fully confirmed.'
            )

            ->action(
                'View My Bookings',
                route('teacher.bookings')
            )

            ->line(
                'Thank you for teaching with DancePair!'
            );
    }

    /**
     * Dashboard / Database notification
     */
    public function toArray(object $notifiable): array
    {
        $studentName =
            $this->booking->student?->user?->name
            ?? 'Student';

        $danceStyle =
            $this->booking->danceStyle?->name
            ?? 'Dance Lesson';

        return [
            'type' => 'payment_received',

            'title' => 'Payment Received',

            'message' =>
                $studentName .
                ' paid $' .
                number_format(
                    (float) $this->payment->amount,
                    2
                ) .
                ' for ' .
                $danceStyle .
                '. Your earnings are $' .
                number_format(
                    (float) $this->payment->teacher_amount,
                    2
                ) .
                '.',

            'booking_id' =>
                $this->booking->id,

            'payment_id' =>
                $this->payment->id,

            'student_name' =>
                $studentName,

            'dance_style' =>
                $danceStyle,

            'amount' =>
                (float) $this->payment->amount,

            'platform_fee' =>
                (float) $this->payment->platform_fee,

            'teacher_amount' =>
                (float) $this->payment->teacher_amount,

            'currency' =>
                $this->payment->currency ?? 'CAD',

            'lesson_date' =>
                $this->booking->lesson_date,

            'lesson_time' =>
                $this->booking->lesson_time,

            'url' =>
                route('teacher.bookings'),
        ];
    }
}