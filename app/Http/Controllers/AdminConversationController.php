<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingMessage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminConversationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE ADMIN
    |--------------------------------------------------------------------------
    */

    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check()
            &&
            Auth::user()->role === 'admin',
            403
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CONVERSATIONS LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $this->authorizeAdmin();


        $search =
            trim(
                (string) $request->query(
                    'q',
                    ''
                )
            );


        $query =
            Booking::query()

                /*
                 * Only bookings that actually contain messages.
                 */
                ->whereHas(
                    'messages'
                )

                ->with([
                    'teacher.user',
                    'student.user',
                    'danceStyle',
                    'messages.sender',
                ])

                ->withCount(
                    'messages'
                )

                ->withMax(
                    'messages',
                    'created_at'
                );


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        |
        | Search by:
        |
        | Booking ID
        | Teacher name/email
        | Student name/email
        | Dance style
        | Message text
        |
        */

        if ($search !== '') {

            $query->where(
                function ($subQuery) use ($search) {

                    $subQuery

                        ->whereHas(
                            'teacher.user',
                            function ($userQuery) use ($search) {

                                $userQuery
                                    ->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhere(
                                        'email',
                                        'like',
                                        '%' . $search . '%'
                                    );
                            }
                        )

                        ->orWhereHas(
                            'student.user',
                            function ($userQuery) use ($search) {

                                $userQuery
                                    ->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhere(
                                        'email',
                                        'like',
                                        '%' . $search . '%'
                                    );
                            }
                        )

                        ->orWhereHas(
                            'danceStyle',
                            function ($styleQuery) use ($search) {

                                $styleQuery->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                );
                            }
                        )

                        ->orWhereHas(
                            'messages',
                            function ($messageQuery) use ($search) {

                                $messageQuery->where(
                                    'message',
                                    'like',
                                    '%' . $search . '%'
                                );
                            }
                        );


                    if (ctype_digit($search)) {

                        $subQuery->orWhere(
                            'id',
                            (int) $search
                        );
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ORDER
        |--------------------------------------------------------------------------
        */

        $conversations =
            $query
                ->orderByDesc(
                    'messages_max_created_at'
                )
                ->paginate(20)
                ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

        $conversationCount =
            Booking::query()
                ->whereHas('messages')
                ->count();


        $messageCount =
            BookingMessage::query()
                ->count();


        return view(
            'admin.conversations.index',
            compact(
                'conversations',
                'conversationCount',
                'messageCount',
                'search'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | OPEN CONVERSATION
    |--------------------------------------------------------------------------
    */

    public function show(Booking $booking)
    {
        $this->authorizeAdmin();


        $booking->load([
            'teacher.user',
            'student.user',
            'danceStyle',
            'messages.sender',
        ]);


        return view(
            'admin.conversations.show',
            compact(
                'booking'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN REPLY
    |--------------------------------------------------------------------------
    */

    public function reply(
        Request $request,
        Booking $booking
    ) {
        $this->authorizeAdmin();


        $validated =
            $request->validate([

                'message' => [
                    'required',
                    'string',
                    'max:3000',
                ],

            ]);


        /*
        |--------------------------------------------------------------------------
        | SAVE INTO EXISTING BOOKING CONVERSATION
        |--------------------------------------------------------------------------
        */

        $message =
            new BookingMessage();


        $message->booking_id =
            $booking->id;


        $message->sender_id =
            Auth::id();


        $message->message =
            trim(
                $validated['message']
            );


        $message->save();


        return redirect()
            ->route(
                'admin.conversations.show',
                $booking
            )
            ->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'La réponse DancePair a été envoyée.'
                    : 'DancePair Support reply sent.'
            );
    }
}