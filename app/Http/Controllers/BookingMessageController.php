<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingMessage;
use App\Notifications\BookingMessageNotification;
use App\Services\MessageContentFilter;
use Illuminate\Http\Request;

class BookingMessageController extends Controller
{
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
        |
        | Only the student and teacher belonging to this booking
        | are allowed to send messages.
        |
        */

        $studentUserId = $booking->student?->user_id;
        $teacherUserId = $booking->teacher?->user_id;

        if (
            $user->id !== $studentUserId &&
            $user->id !== $teacherUserId
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

        $messageText = trim($validated['message']);


        /*
        |--------------------------------------------------------------------------
        | BLOCK DIRECT CONTACT INFORMATION
        |--------------------------------------------------------------------------
        */

        if (
            $contentFilter->containsForbiddenContactInfo(
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

        $bookingMessage = BookingMessage::create([
            'booking_id' => $booking->id,
            'sender_id' => $user->id,
            'message' => $messageText,
        ]);


        /*
        |--------------------------------------------------------------------------
        | FIND RECIPIENT
        |--------------------------------------------------------------------------
        */

        if ($user->id === $studentUserId) {

            $recipient = $booking->teacher?->user;

        } else {

            $recipient = $booking->student?->user;
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
        | REDIRECT BACK TO CONVERSATION
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            __('messages.booking_message_sent')
        );
    }
}