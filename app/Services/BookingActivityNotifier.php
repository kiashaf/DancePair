<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\BookingActivityNotification;
use Illuminate\Support\Facades\Log;

class BookingActivityNotifier
{
    public function notifyBoth(
        Booking $booking,
        string $action,
        string $actorRole,
        ?string $actorName = null,
        ?Payment $payment = null
    ): void {

        /*
        |--------------------------------------------------------------------------
        | LOAD REQUIRED RELATIONSHIPS
        |--------------------------------------------------------------------------
        */

        $booking->loadMissing([
            'student.user',
            'teacher.user',
            'danceStyle',
        ]);


        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $studentUser =
            $booking
                ->student
                ?->user;

        $teacherUser =
            $booking
                ->teacher
                ?->user;


        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION
        |--------------------------------------------------------------------------
        */

        $sendNotification =
            function ($user) use (
                $booking,
                $action,
                $actorRole,
                $actorName,
                $payment
            ): void {

                if (!$user) {
                    return;
                }

                try {

                    $user->notify(
                        new BookingActivityNotification(
                            booking: $booking,
                            action: $action,
                            actorRole: $actorRole,
                            actorName: $actorName,
                            payment: $payment
                        )
                    );

                } catch (\Throwable $exception) {

                    /*
                    |--------------------------------------------------------------------------
                    | DO NOT BREAK BOOKING ACTION
                    |--------------------------------------------------------------------------
                    |
                    | Email/database notification failure must never prevent:
                    |
                    | - Accept
                    | - Reject
                    | - Cancel
                    | - Refund
                    |
                    */

                    report(
                        $exception
                    );

                    Log::error(
                        'Booking activity notification failed.',
                        [
                            'booking_id' =>
                                $booking->id,

                            'user_id' =>
                                $user->id
                                ?? null,

                            'action' =>
                                $action,

                            'actor_role' =>
                                $actorRole,

                            'error' =>
                                $exception
                                    ->getMessage(),
                        ]
                    );
                }
            };


        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        $sendNotification(
            $studentUser
        );


        /*
        |--------------------------------------------------------------------------
        | TEACHER
        |--------------------------------------------------------------------------
        |
        | Prevent accidental duplicate notification if both profiles ever point
        | to the same User record.
        |
        */

        if (
            $teacherUser
            &&
            (
                !$studentUser
                ||
                (int) $teacherUser->id
                !==
                (int) $studentUser->id
            )
        ) {

            $sendNotification(
                $teacherUser
            );
        }
    }
}