<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentPaymentConfirmedNotification extends Notification
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
     * Email
     */
    public function toMail(object $notifiable): MailMessage
    {
        $danceStyle = $this->booking->danceStyle?->name
            ?? 'Dance Lesson';

        $teacherName = $this->booking->teacher?->user?->name
            ?? 'your teacher';

        return (new MailMessage)
            ->subject('Payment Confirmed - DancePair')
            ->greeting('Hi ' . $notifiable->name . '!')
            ->line('Your payment has been completed successfully.')
            ->line(
                'Lesson: ' .
                $danceStyle .
                ' with ' .
                $teacherName
            )
            ->line(
                'Amount paid: $' .
                number_format(
                    (float) $this->payment->amount,
                    2
                ) .
                ' CAD'
            )
            ->line('Your lesson is now confirmed.')
            ->action(
                'View My Bookings',
                route('student.bookings')
            )
            ->line('Thank you for using DancePair!');
    }

    /**
     * Dashboard / Database notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_confirmed',

            'title' => 'Payment Confirmed',

            'message' =>
                'Your payment of $' .
                number_format(
                    (float) $this->payment->amount,
                    2
                ) .
                ' for ' .
                ($this->booking->danceStyle?->name ?? 'your lesson') .
                ' was successful. Your lesson is now confirmed.',

            'booking_id' => $this->booking->id,

            'payment_id' => $this->payment->id,

            'dance_style' =>
                $this->booking->danceStyle?->name
                ?? 'Dance Lesson',

            'teacher_name' =>
                $this->booking->teacher?->user?->name
                ?? 'Teacher',

            'amount' => (float) $this->payment->amount,

            'currency' =>
                $this->payment->currency ?? 'CAD',

            'lesson_date' =>
                $this->booking->lesson_date,

            'lesson_time' =>
                $this->booking->lesson_time,

            'url' =>
                route('student.bookings'),
        ];
    }
}