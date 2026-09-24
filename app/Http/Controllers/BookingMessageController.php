<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingMessage;
use App\Notifications\BookingMessageNotification;
use App\Services\MessageContentFilter;
use Illuminate\Http\Request;

class BookingMessageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SEND MESSAGE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Booking $booking,
        MessageContentFilter $contentFilter
    ) {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | LOAD BOOKING RELATIONSHIPS
        |--------------------------------------------------------------------------
        */

        $booking->loadMissing([
            'student.user',
            'teacher.user',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        $studentUserId =
            $booking->student?->user_id;

        $teacherUserId =
            $booking->teacher?->user_id;


        if (
            (int) $user->id !== (int) $studentUserId
            &&
            (int) $user->id !== (int) $teacherUserId
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:3000',
            ],
        ]);


        $messageText =
            trim(
                $validated['message']
            );


        /*
        |--------------------------------------------------------------------------
        | BLOCK DIRECT CONTACT INFORMATION
        |--------------------------------------------------------------------------
        */

        if (
            $contentFilter
                ->containsForbiddenContactInfo(
                    $messageText
                )
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'message' =>
                        __('messages.contact_information_not_allowed'),
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE MESSAGE
        |--------------------------------------------------------------------------
        */

        $bookingMessage =
            BookingMessage::create([
                'booking_id' =>
                    $booking->id,

                'sender_id' =>
                    $user->id,

                'message' =>
                    $messageText,
            ]);


        /*
        |--------------------------------------------------------------------------
        | FIND RECIPIENT
        |--------------------------------------------------------------------------
        */

        if (
            (int) $user->id
            ===
            (int) $studentUserId
        ) {

            $recipient =
                $booking
                    ->teacher
                    ?->user;

        } else {

            $recipient =
                $booking
                    ->student
                    ?->user;
        }


        /*
        |--------------------------------------------------------------------------
        | DATABASE NOTIFICATION + EMAIL
        |--------------------------------------------------------------------------
        */

        if ($recipient) {

            $recipient->notify(
                new BookingMessageNotification(
                    $booking,
                    $bookingMessage,
                    $user->name
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECT BACK
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            __('messages.booking_message_sent')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MARK RECEIVED MESSAGES AS READ
    |--------------------------------------------------------------------------
    |
    | When Student or Teacher opens the conversation:
    |
    | - All messages received from the other user become read.
    | - Messages sent by the current user are NOT changed.
    | - The unread badge can then become zero.
    |
    */

    public function markRead(
        Request $request,
        Booking $booking
    ) {
        $user =
            $request->user();


        /*
        |--------------------------------------------------------------------------
        | LOAD BOOKING RELATIONSHIPS
        |--------------------------------------------------------------------------
        */

        $booking->loadMissing([
            'student.user',
            'teacher.user',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        $studentUserId =
            $booking->student?->user_id;

        $teacherUserId =
            $booking->teacher?->user_id;


        if (
            (int) $user->id !== (int) $studentUserId
            &&
            (int) $user->id !== (int) $teacherUserId
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | MARK RECEIVED MESSAGES AS READ
        |--------------------------------------------------------------------------
        */

        BookingMessage::where(
                'booking_id',
                $booking->id
            )
            ->where(
                'sender_id',
                '!=',
                $user->id
            )
            ->whereNull(
                'read_at'
            )
            ->update([
                'read_at' =>
                    now(),
            ]);


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' =>
                true,

            'booking_id' =>
                $booking->id,

            'unread_count' =>
                0,
        ]);
    }
}