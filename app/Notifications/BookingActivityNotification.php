<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingActivityNotification extends Notification
{
    use Queueable;


    /*
    |--------------------------------------------------------------------------
    | PROPERTIES
    |--------------------------------------------------------------------------
    */

    public Booking $booking;

    public string $action;

    public string $actorRole;

    public ?string $actorName;

    public ?Payment $payment;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        Booking $booking,
        string $action,
        string $actorRole,
        ?string $actorName = null,
        ?Payment $payment = null
    ) {
        $this->booking =
            $booking;

        $this->action =
            $action;

        $this->actorRole =
            $actorRole;

        $this->actorName =
            $actorName;

        $this->payment =
            $payment;
    }


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION CHANNELS
    |--------------------------------------------------------------------------
    |
    | Every notification is sent:
    |
    | 1. Inside the DancePair account
    | 2. By email
    |
    */

    public function via(
        object $notifiable
    ): array {

        return [
            'database',
            'mail',
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

        $content =
            $this->content();


        /*
        |--------------------------------------------------------------------------
        | SUBJECT
        |--------------------------------------------------------------------------
        */

        $subject =
            'DancePair Support — '
            . $content['subject_en']
            . ' / '
            . $content['subject_fr'];


        /*
        |--------------------------------------------------------------------------
        | CREATE EMAIL
        |--------------------------------------------------------------------------
        */

        $mail =
            (new MailMessage)
                ->subject(
                    $subject
                )
                ->greeting(
                    'DancePair Support'
                );


        /*
        |--------------------------------------------------------------------------
        | ENGLISH
        |--------------------------------------------------------------------------
        */

        $mail->line(
            $content['message_en']
        );


        /*
        |--------------------------------------------------------------------------
        | LESSON DATE - EN
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->booking->lesson_date
            )
        ) {

            $mail->line(
                'Lesson date: '
                . Carbon::parse(
                    $this->booking->lesson_date
                )->format(
                    'M d, Y'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LESSON TIME - EN
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->booking->lesson_time
            )
        ) {

            $mail->line(
                'Lesson time: '
                . Carbon::parse(
                    $this->booking->lesson_time
                )->format(
                    'g:i A'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DANCE STYLE - EN
        |--------------------------------------------------------------------------
        */

        if (
            $this->booking
                ->danceStyle
                ?->name
        ) {

            $mail->line(
                'Dance style: '
                . $this->booking
                    ->danceStyle
                    ->name
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT AMOUNT - EN
        |--------------------------------------------------------------------------
        */

        if (
            $this->payment
            &&
            (float) $this->payment->amount > 0
        ) {

            $mail->line(
                'Amount: $'
                . number_format(
                    (float) $this
                        ->payment
                        ->amount,
                    2
                )
                . ' '
                . strtoupper(
                    $this
                        ->payment
                        ->currency
                    ??
                    'CAD'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SEPARATOR
        |--------------------------------------------------------------------------
        */

        $mail->line(
            '────────────'
        );


        /*
        |--------------------------------------------------------------------------
        | FRENCH
        |--------------------------------------------------------------------------
        */

        $mail->line(
            'Français'
        );


        $mail->line(
            $content['message_fr']
        );


        /*
        |--------------------------------------------------------------------------
        | LESSON DATE - FR
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->booking->lesson_date
            )
        ) {

            $mail->line(
                'Date du cours : '
                . Carbon::parse(
                    $this->booking->lesson_date
                )
                    ->locale('fr')
                    ->translatedFormat(
                        'd M Y'
                    )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LESSON TIME - FR
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $this->booking->lesson_time
            )
        ) {

            $mail->line(
                'Heure du cours : '
                . Carbon::parse(
                    $this->booking->lesson_time
                )->format(
                    'H:i'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DANCE STYLE - FR
        |--------------------------------------------------------------------------
        */

        if (
            $this->booking
                ->danceStyle
                ?->name
        ) {

            $mail->line(
                'Style de danse : '
                . $this->booking
                    ->danceStyle
                    ->name
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT AMOUNT - FR
        |--------------------------------------------------------------------------
        */

        if (
            $this->payment
            &&
            (float) $this->payment->amount > 0
        ) {

            $mail->line(
                'Montant : $'
                . number_format(
                    (float) $this
                        ->payment
                        ->amount,
                    2
                )
                . ' '
                . strtoupper(
                    $this
                        ->payment
                        ->currency
                    ??
                    'CAD'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ACTION BUTTON
        |--------------------------------------------------------------------------
        */

        $url =
            $this->notificationUrl(
                $notifiable
            );


        if ($url) {

            $mail->action(
                'View Booking / Voir la réservation',
                $url
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        return $mail
            ->line(
                'Thank you for using DancePair.'
            )
            ->line(
                'Merci d’utiliser DancePair.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DATABASE NOTIFICATION
    |--------------------------------------------------------------------------
    |
    | This is what Student / Teacher sees
    | inside their DancePair account.
    |
    */

    public function toDatabase(
        object $notifiable
    ): array {

        $content =
            $this->content();


        return [

            /*
            |--------------------------------------------------------------------------
            | TYPE
            |--------------------------------------------------------------------------
            */

            'type' =>
                'booking_activity',


            /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */

            'action' =>
                $this->action,


            /*
            |--------------------------------------------------------------------------
            | BOOKING
            |--------------------------------------------------------------------------
            */

            'booking_id' =>
                $this->booking->id,


            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            'payment_id' =>
                $this->payment?->id,


            'payment_amount' =>
                $this->payment
                    ? (float) $this->payment->amount
                    : null,


            'currency' =>
                $this->payment?->currency
                ??
                null,


            /*
            |--------------------------------------------------------------------------
            | WHO DID THE ACTION
            |--------------------------------------------------------------------------
            */

            'actor_role' =>
                $this->actorRole,


            'actor_name' =>
                $this->actorName,


            /*
            |--------------------------------------------------------------------------
            | ENGLISH
            |--------------------------------------------------------------------------
            */

            'title_en' =>
                $content['title_en'],


            'message_en' =>
                $content['message_en'],


            /*
            |--------------------------------------------------------------------------
            | FRENCH
            |--------------------------------------------------------------------------
            */

            'title_fr' =>
                $content['title_fr'],


            'message_fr' =>
                $content['message_fr'],


            /*
            |--------------------------------------------------------------------------
            | CURRENT DASHBOARD COMPATIBILITY
            |--------------------------------------------------------------------------
            |
            | Existing Student / Teacher dashboard currently reads:
            |
            | title
            | message
            | url
            |
            | So we keep these fields too.
            |
            */

            'title' =>
                $content['title_en']
                . ' / '
                . $content['title_fr'],


            'message' =>
                $content['message_en']
                . ' / '
                . $content['message_fr'],


            /*
            |--------------------------------------------------------------------------
            | URL
            |--------------------------------------------------------------------------
            */

            'url' =>
                $this->notificationUrl(
                    $notifiable
                ),
        ];
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


    /*
    |--------------------------------------------------------------------------
    | CONTENT
    |--------------------------------------------------------------------------
    */

    private function content(): array
    {
        /*
        |--------------------------------------------------------------------------
        | ACTOR NAME
        |--------------------------------------------------------------------------
        */

        $actorName =
            $this->actorName;


        if (!$actorName) {

            $actorName =
                match (
                    $this->actorRole
                ) {

                    'student' =>
                        'Student',

                    'teacher' =>
                        'Teacher',

                    'admin' =>
                        'DancePair Support',

                    default =>
                        'DancePair',
                };
        }


        /*
        |--------------------------------------------------------------------------
        | ACTION CONTENT
        |--------------------------------------------------------------------------
        */

        return match (
            $this->action
        ) {


            /*
            |--------------------------------------------------------------------------
            | STUDENT CREATED NEW REQUEST
            |--------------------------------------------------------------------------
            */

            'request_created' => [

                'subject_en' =>
                    'New lesson request',

                'subject_fr' =>
                    'Nouvelle demande de cours',

                'title_en' =>
                    'Lesson request submitted',

                'title_fr' =>
                    'Demande de cours envoyée',

                'message_en' =>
                    $actorName
                    . ' submitted a new lesson request.',

                'message_fr' =>
                    $actorName
                    . ' a envoyé une nouvelle demande de cours.',
            ],


            /*
            |--------------------------------------------------------------------------
            | STUDENT WITHDREW PENDING REQUEST
            |--------------------------------------------------------------------------
            */

            'request_withdrawn' => [

                'subject_en' =>
                    'Lesson request cancelled',

                'subject_fr' =>
                    'Demande de cours annulée',

                'title_en' =>
                    'Lesson request cancelled',

                'title_fr' =>
                    'Demande de cours annulée',

                'message_en' =>
                    $actorName
                    . ' cancelled the pending lesson request.',

                'message_fr' =>
                    $actorName
                    . ' a annulé la demande de cours en attente.',
            ],


            /*
            |--------------------------------------------------------------------------
            | TEACHER ACCEPTED REQUEST
            |--------------------------------------------------------------------------
            */

            'request_accepted' => [

                'subject_en' =>
                    'Lesson request accepted',

                'subject_fr' =>
                    'Demande de cours acceptée',

                'title_en' =>
                    'Lesson request accepted',

                'title_fr' =>
                    'Demande de cours acceptée',

                'message_en' =>
                    $actorName
                    . ' accepted the lesson request.',

                'message_fr' =>
                    $actorName
                    . ' a accepté la demande de cours.',
            ],


            /*
            |--------------------------------------------------------------------------
            | TEACHER REJECTED REQUEST
            |--------------------------------------------------------------------------
            */

            'request_rejected' => [

                'subject_en' =>
                    'Lesson request declined',

                'subject_fr' =>
                    'Demande de cours refusée',

                'title_en' =>
                    'Lesson request declined',

                'title_fr' =>
                    'Demande de cours refusée',

                'message_en' =>
                    $actorName
                    . ' declined the lesson request.',

                'message_fr' =>
                    $actorName
                    . ' a refusé la demande de cours.',
            ],


            /*
            |--------------------------------------------------------------------------
            | STUDENT CANCELLED - UNPAID
            |--------------------------------------------------------------------------
            */

            'student_cancelled_unpaid' => [

                'subject_en' =>
                    'Lesson cancelled by student',

                'subject_fr' =>
                    'Cours annulé par l’élève',

                'title_en' =>
                    'Lesson cancelled by student',

                'title_fr' =>
                    'Cours annulé par l’élève',

                'message_en' =>
                    $actorName
                    . ' cancelled the lesson. '
                    . 'No payment had been completed.',

                'message_fr' =>
                    $actorName
                    . ' a annulé le cours. '
                    . 'Aucun paiement n’avait été effectué.',
            ],


            /*
            |--------------------------------------------------------------------------
            | STUDENT CANCELLED - NO REFUND
            |--------------------------------------------------------------------------
            */

            'student_cancelled_no_refund' => [

                'subject_en' =>
                    'Lesson cancelled — no automatic refund',

                'subject_fr' =>
                    'Cours annulé — aucun remboursement automatique',

                'title_en' =>
                    'Lesson cancelled by student',

                'title_fr' =>
                    'Cours annulé par l’élève',

                'message_en' =>
                    $actorName
                    . ' cancelled the lesson after the applicable cancellation deadline. '
                    . 'No automatic refund was issued.',

                'message_fr' =>
                    $actorName
                    . ' a annulé le cours après le délai d’annulation applicable. '
                    . 'Aucun remboursement automatique n’a été effectué.',
            ],


            /*
            |--------------------------------------------------------------------------
            | STUDENT CANCELLED - REFUND PENDING
            |--------------------------------------------------------------------------
            */

            'student_cancelled_refund_pending' => [

                'subject_en' =>
                    'Lesson cancelled — refund processing',

                'subject_fr' =>
                    'Cours annulé — remboursement en cours',

                'title_en' =>
                    'Lesson cancelled — refund processing',

                'title_fr' =>
                    'Cours annulé — remboursement en cours',

                'message_en' =>
                    $actorName
                    . ' cancelled the lesson. '
                    . 'A 100% refund is being processed to the student’s original payment method.',

                'message_fr' =>
                    $actorName
                    . ' a annulé le cours. '
                    . 'Un remboursement de 100 % est en cours vers le mode de paiement d’origine de l’élève.',
            ],


            /*
            |--------------------------------------------------------------------------
            | STUDENT CANCELLED - REFUND COMPLETED
            |--------------------------------------------------------------------------
            */

            'student_cancelled_refunded' => [

                'subject_en' =>
                    'Lesson cancelled and refunded',

                'subject_fr' =>
                    'Cours annulé et remboursé',

                'title_en' =>
                    'Lesson cancelled — 100% refunded',

                'title_fr' =>
                    'Cours annulé — remboursement de 100 %',

                'message_en' =>
                    $actorName
                    . ' cancelled the lesson. '
                    . 'A 100% refund was issued to the student’s original payment method.',

                'message_fr' =>
                    $actorName
                    . ' a annulé le cours. '
                    . 'Un remboursement de 100 % a été effectué vers le mode de paiement d’origine de l’élève.',
            ],


            /*
            |--------------------------------------------------------------------------
            | TEACHER CANCELLED - UNPAID
            |--------------------------------------------------------------------------
            */

            'teacher_cancelled' => [

                'subject_en' =>
                    'Lesson cancelled by teacher',

                'subject_fr' =>
                    'Cours annulé par le professeur',

                'title_en' =>
                    'Lesson cancelled by teacher',

                'title_fr' =>
                    'Cours annulé par le professeur',

                'message_en' =>
                    $actorName
                    . ' cancelled the lesson. '
                    . 'No payment had been completed.',

                'message_fr' =>
                    $actorName
                    . ' a annulé le cours. '
                    . 'Aucun paiement n’avait été effectué.',
            ],


            /*
            |--------------------------------------------------------------------------
            | TEACHER CANCELLED - REFUND PENDING
            |--------------------------------------------------------------------------
            */

            'teacher_cancelled_refund_pending' => [

                'subject_en' =>
                    'Lesson cancelled — refund processing',

                'subject_fr' =>
                    'Cours annulé — remboursement en cours',

                'title_en' =>
                    'Teacher cancelled — refund processing',

                'title_fr' =>
                    'Cours annulé par le professeur — remboursement en cours',

                'message_en' =>
                    $actorName
                    . ' cancelled the lesson. '
                    . 'DancePair is processing a 100% refund to the student’s original payment method.',

                'message_fr' =>
                    $actorName
                    . ' a annulé le cours. '
                    . 'DancePair traite un remboursement de 100 % vers le mode de paiement d’origine de l’élève.',
            ],


            /*
            |--------------------------------------------------------------------------
            | TEACHER CANCELLED - REFUND COMPLETED
            |--------------------------------------------------------------------------
            */

            'teacher_cancelled_refunded' => [

                'subject_en' =>
                    'Lesson cancelled and refunded',

                'subject_fr' =>
                    'Cours annulé et remboursé',

                'title_en' =>
                    'Teacher cancelled — 100% refunded',

                'title_fr' =>
                    'Cours annulé — remboursement de 100 %',

                'message_en' =>
                    $actorName
                    . ' cancelled the lesson. '
                    . 'The student received a 100% refund to the original payment method.',

                'message_fr' =>
                    $actorName
                    . ' a annulé le cours. '
                    . 'L’élève a reçu un remboursement de 100 % vers son mode de paiement d’origine.',
            ],


            /*
            |--------------------------------------------------------------------------
            | ADMIN REFUND - PROCESSING
            |--------------------------------------------------------------------------
            */

            'admin_refund_pending' => [

                'subject_en' =>
                    'Refund processing',

                'subject_fr' =>
                    'Remboursement en cours',

                'title_en' =>
                    'DancePair Support initiated a refund',

                'title_fr' =>
                    'DancePair Support a lancé un remboursement',

                'message_en' =>
                    'DancePair Support initiated a 100% refund. '
                    . 'The refund is currently being processed to the student’s original payment method.',

                'message_fr' =>
                    'DancePair Support a lancé un remboursement de 100 %. '
                    . 'Le remboursement est actuellement en cours vers le mode de paiement d’origine de l’élève.',
            ],


            /*
            |--------------------------------------------------------------------------
            | ADMIN REFUND - COMPLETED
            |--------------------------------------------------------------------------
            */

            'admin_refunded' => [

                'subject_en' =>
                    '100% refund completed',

                'subject_fr' =>
                    'Remboursement de 100 % effectué',

                'title_en' =>
                    'DancePair Support issued a 100% refund',

                'title_fr' =>
                    'DancePair Support a effectué un remboursement de 100 %',

                'message_en' =>
                    'DancePair Support issued a 100% refund to the student’s original payment method. '
                    . 'The related lesson has been cancelled.',

                'message_fr' =>
                    'DancePair Support a effectué un remboursement de 100 % vers le mode de paiement d’origine de l’élève. '
                    . 'Le cours associé a été annulé.',
            ],


            /*
            |--------------------------------------------------------------------------
            | DEFAULT
            |--------------------------------------------------------------------------
            */

            default => [

                'subject_en' =>
                    'Booking update',

                'subject_fr' =>
                    'Mise à jour de la réservation',

                'title_en' =>
                    'Booking updated',

                'title_fr' =>
                    'Réservation mise à jour',

                'message_en' =>
                    'An update was made to your DancePair booking.',

                'message_fr' =>
                    'Une mise à jour a été apportée à votre réservation DancePair.',
            ],
        };
    }


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION URL
    |--------------------------------------------------------------------------
    */

    private function notificationUrl(
        object $notifiable
    ): string {

        /*
        |--------------------------------------------------------------------------
        | TEACHER
        |--------------------------------------------------------------------------
        */

        if (
            ($notifiable->role ?? null)
            ===
            'teacher'
        ) {

            return route(
                'teacher.bookings'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        if (
            ($notifiable->role ?? null)
            ===
            'student'
        ) {

            return route(
                'student.bookings'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN / FALLBACK
        |--------------------------------------------------------------------------
        */

        return url('/');
    }
}