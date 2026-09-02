<?php

namespace App\Http\Controllers;

use App\Models\PlatformMessageRecipient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformMessageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INBOX
    |--------------------------------------------------------------------------
    */

    public function inbox(Request $request): JsonResponse
    {
        $this->authorizeClient();


        $user =
            Auth::user();


        $now =
            now();


        /*
        |--------------------------------------------------------------------------
        | ALL ACTIVE DANCEPAIR MESSAGES FOR THIS USER
        |--------------------------------------------------------------------------
        */

        $recipients =
            PlatformMessageRecipient::query()

                ->with('message')

                ->where(
                    'user_id',
                    $user->id
                )

                ->whereHas(
                    'message',
                    function ($query) use ($now) {

                        $query

                            ->where(
                                'is_active',
                                true
                            )

                            ->where(
                                function ($query) use ($now) {

                                    $query
                                        ->whereNull(
                                            'starts_at'
                                        )
                                        ->orWhere(
                                            'starts_at',
                                            '<=',
                                            $now
                                        );
                                }
                            )

                            ->where(
                                function ($query) use ($now) {

                                    $query
                                        ->whereNull(
                                            'ends_at'
                                        )
                                        ->orWhere(
                                            'ends_at',
                                            '>=',
                                            $now
                                        );
                                }
                            );
                    }
                )

                ->get()

                ->filter(
                    function ($recipient) {

                        return
                            $recipient->message !== null;
                    }
                )

                ->sortByDesc(
                    function ($recipient) {

                        return
                            $recipient->message->created_at
                            ?? $recipient->created_at;
                    }
                )

                ->values();


        /*
        |--------------------------------------------------------------------------
        | UNREAD
        |--------------------------------------------------------------------------
        */

        $unreadCount =
            $recipients
                ->whereNull(
                    'read_at'
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | AUTO MESSAGE
        |--------------------------------------------------------------------------
        |
        | Priority:
        |
        | 1. Critical
        | 2. Important
        | 3. Normal
        |
        */

        $autoRecipient =
            $this->findAutoRecipient(
                $recipients
            );


        /*
        |--------------------------------------------------------------------------
        | JSON MESSAGE LIST
        |--------------------------------------------------------------------------
        */

        $messages =
            $recipients
                ->map(
                    function ($recipient) {

                        $message =
                            $recipient->message;


                        return [

                            'recipient_id' =>
                                $recipient->id,

                            'message_id' =>
                                $message->id,

                            'title' =>
                                $message->titleForLocale(
                                    app()->getLocale()
                                ),

                            'message' =>
                                $message->messageForLocale(
                                    app()->getLocale()
                                ),

                            /*
                             * Importance is returned only because
                             * JavaScript needs display logic.
                             *
                             * WE NEVER SHOW THIS VALUE TO CLIENT.
                             */

                            'importance' =>
                                $message->importance,

                            'is_read' =>
                                $recipient->read_at
                                    !== null,

                            'created_at' =>
                                optional(
                                    $message->created_at
                                )->toIso8601String(),

                            'created_at_formatted' =>
                                optional(
                                    $message->created_at
                                )->locale(
                                    app()->getLocale()
                                )->translatedFormat(
                                    app()->getLocale() === 'fr'
                                        ? 'd M Y · H:i'
                                        : 'M d, Y · g:i A'
                                ),
                        ];
                    }
                )
                ->values();


        return response()->json([

            'messages' =>
                $messages,

            'unread_count' =>
                $unreadCount,

            'auto_recipient_id' =>
                $autoRecipient?->id,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SHOWN / OPENED
    |--------------------------------------------------------------------------
    */

    public function shown(
        Request $request,
        PlatformMessageRecipient $recipient
    ): JsonResponse {

        $this->authorizeClient();

        $this->authorizeRecipient(
            $recipient
        );


        $recipient->loadMissing(
            'message'
        );


        abort_unless(
            $recipient->message,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | MESSAGE WAS ACTUALLY DISPLAYED
        |--------------------------------------------------------------------------
        */

        $updates = [

            'last_shown_at' =>
                now(),

            'show_count' =>
                ((int) $recipient->show_count)
                +
                1,

            /*
             * Re-opened now.
             */

            'dismissed_at' =>
                null,
        ];


        /*
        |--------------------------------------------------------------------------
        | FIRST TIME SEEN
        |--------------------------------------------------------------------------
        */

        if (!$recipient->seen_at) {

            $updates['seen_at'] =
                now();
        }


        /*
        |--------------------------------------------------------------------------
        | OPEN = READ
        |--------------------------------------------------------------------------
        */

        if (!$recipient->read_at) {

            $updates['read_at'] =
                now();
        }


        $recipient->update(
            $updates
        );


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Only auto-show once during THIS login/session.
        |
        */

        if (
            $recipient->message->importance
            ===
            'important'
        ) {

            session()->put(
                $this->importantSessionKey(
                    $recipient->id
                ),
                true
            );
        }


        return response()->json([

            'success' =>
                true,

            'recipient_id' =>
                $recipient->id,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DISMISS
    |--------------------------------------------------------------------------
    */

    public function dismiss(
        Request $request,
        PlatformMessageRecipient $recipient
    ): JsonResponse {

        $this->authorizeClient();

        $this->authorizeRecipient(
            $recipient
        );


        $recipient->update([

            'dismissed_at' =>
                now(),
        ]);


        return response()->json([

            'success' =>
                true,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FIND AUTO MESSAGE
    |--------------------------------------------------------------------------
    */

    private function findAutoRecipient(
        $recipients
    ) {

        /*
        |--------------------------------------------------------------------------
        | CRITICAL
        |--------------------------------------------------------------------------
        |
        | Every page.
        |
        */

        $critical =
            $recipients
                ->first(
                    function ($recipient) {

                        return
                            $recipient->message->importance
                            ===
                            'critical';
                    }
                );


        if ($critical) {

            return $critical;
        }


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Once per current login/session.
        |
        */

        $important =
            $recipients
                ->first(
                    function ($recipient) {

                        if (
                            $recipient->message->importance
                            !==
                            'important'
                        ) {

                            return false;
                        }


                        return
                            !session()->has(
                                $this->importantSessionKey(
                                    $recipient->id
                                )
                            );
                    }
                );


        if ($important) {

            return $important;
        }


        /*
        |--------------------------------------------------------------------------
        | NORMAL
        |--------------------------------------------------------------------------
        |
        | Auto-show only once EVER.
        |
        */

        return
            $recipients
                ->first(
                    function ($recipient) {

                        return
                            $recipient->message->importance
                                ===
                                'normal'
                            &&
                            $recipient->seen_at
                                ===
                                null;
                    }
                );
    }


    /*
    |--------------------------------------------------------------------------
    | IMPORTANT SESSION KEY
    |--------------------------------------------------------------------------
    */

    private function importantSessionKey(
        int $recipientId
    ): string {

        return
            'dancepair_platform_message'
            . '.important'
            . '.shown'
            . '.'
            . $recipientId;
    }


    /*
    |--------------------------------------------------------------------------
    | CLIENT SECURITY
    |--------------------------------------------------------------------------
    */

    private function authorizeClient(): void
    {
        abort_unless(
            Auth::check()
            &&
            in_array(
                Auth::user()->role,
                [
                    'teacher',
                    'student',
                ],
                true
            ),
            403
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RECIPIENT SECURITY
    |--------------------------------------------------------------------------
    */

    private function authorizeRecipient(
        PlatformMessageRecipient $recipient
    ): void {

        abort_unless(
            (int) $recipient->user_id
            ===
            (int) Auth::id(),
            403
        );
    }
}