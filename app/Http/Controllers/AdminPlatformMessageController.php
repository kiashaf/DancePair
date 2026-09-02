<?php

namespace App\Http\Controllers;

use App\Models\PlatformMessage;
use App\Models\PlatformMessageRecipient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminPlatformMessageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->authorizeAdmin();


        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $users = User::query()
            ->whereIn(
                'role',
                [
                    'student',
                    'teacher',
                ]
            )
            ->orderBy('role')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'role',
            ]);


        /*
        |--------------------------------------------------------------------------
        | MESSAGES
        |--------------------------------------------------------------------------
        */

        $messages = PlatformMessage::query()

            ->with([
                'creator:id,name',
                'recipients.user:id,name,email,role',
            ])

            ->withCount(
                'recipients'
            )

            ->withCount([
                'recipients as read_count' => function ($query) {

                    $query->whereNotNull(
                        'read_at'
                    );
                },
            ])

            ->orderByDesc('id')

            ->paginate(15);


        /*
        |--------------------------------------------------------------------------
        | COUNTS
        |--------------------------------------------------------------------------
        */

        $teachersCount = $users
            ->where(
                'role',
                'teacher'
            )
            ->count();


        $studentsCount = $users
            ->where(
                'role',
                'student'
            )
            ->count();


        return view(
            'admin.platform-messages.index',
            compact(
                'users',
                'messages',
                'teachersCount',
                'studentsCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE MESSAGE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $this->authorizeAdmin();


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'title_en' => [
                'required',
                'string',
                'max:255',
            ],

            'title_fr' => [
                'required',
                'string',
                'max:255',
            ],

            'message_en' => [
                'required',
                'string',
                'max:10000',
            ],

            'message_fr' => [
                'required',
                'string',
                'max:10000',
            ],

            'importance' => [
                'required',
                Rule::in([
                    'normal',
                    'important',
                    'critical',
                ]),
            ],

            'audience_type' => [
                'required',
                Rule::in([
                    'all_users',
                    'all_students',
                    'all_teachers',
                    'single_user',
                    'selected_users',
                ]),
            ],

            'single_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'selected_user_ids' => [
                'nullable',
                'array',
            ],

            'selected_user_ids.*' => [
                'integer',
                'exists:users,id',
            ],

            'starts_at' => [
                'nullable',
                'date',
            ],

            'ends_at' => [
                'nullable',
                'date',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATE DATE RANGE
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['starts_at'])
            &&
            !empty($validated['ends_at'])
            &&
            strtotime($validated['ends_at'])
                <
            strtotime($validated['starts_at'])
        ) {

            throw ValidationException::withMessages([

                'ends_at' =>
                    'End date cannot be before start date.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | GET RECIPIENT IDS
        |--------------------------------------------------------------------------
        */

        $recipientIds =
            $this->resolveRecipientIds(
                $validated
            );


        if (empty($recipientIds)) {

            throw ValidationException::withMessages([

                'audience_type' =>
                    'Please select at least one recipient.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE MESSAGE + RECIPIENTS
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validated,
                $recipientIds
            ) {

                $message =
                    PlatformMessage::create([

                        'title_en' =>
                            $validated['title_en'],

                        'title_fr' =>
                            $validated['title_fr'],

                        'message_en' =>
                            $validated['message_en'],

                        'message_fr' =>
                            $validated['message_fr'],

                        'importance' =>
                            $validated['importance'],

                        'audience_type' =>
                            $validated['audience_type'],

                        'starts_at' =>
                            $validated['starts_at']
                            ?? null,

                        'ends_at' =>
                            $validated['ends_at']
                            ?? null,

                        'is_active' =>
                            (bool) (
                                $validated['is_active']
                                ?? false
                            ),

                        'created_by' =>
                            Auth::id(),
                    ]);


                /*
                |--------------------------------------------------------------------------
                | CREATE RECIPIENT ROWS
                |--------------------------------------------------------------------------
                */

                $now = now();

                $recipientRows = [];


                foreach ($recipientIds as $userId) {

                    $recipientRows[] = [

                        'platform_message_id' =>
                            $message->id,

                        'user_id' =>
                            $userId,

                        'seen_at' =>
                            null,

                        'read_at' =>
                            null,

                        'dismissed_at' =>
                            null,

                        'last_shown_at' =>
                            null,

                        'show_count' =>
                            0,

                        'created_at' =>
                            $now,

                        'updated_at' =>
                            $now,
                    ];
                }


                PlatformMessageRecipient::insert(
                    $recipientRows
                );
            }
        );


        return redirect()
            ->route(
                'admin.platform-messages'
            )
            ->with(
                'success',
                'DancePair message sent successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TOGGLE ACTIVE
    |--------------------------------------------------------------------------
    */

    public function toggle(
        PlatformMessage $platformMessage
    ) {

        $this->authorizeAdmin();


        $platformMessage->update([

            'is_active' =>
                !$platformMessage->is_active,
        ]);


        return back()->with(
            'success',
            $platformMessage->is_active
                ? 'Message activated.'
                : 'Message deactivated.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        PlatformMessage $platformMessage
    ) {

        $this->authorizeAdmin();


        $platformMessage->delete();


        return back()->with(
            'success',
            'Message deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE RECIPIENT IDS
    |--------------------------------------------------------------------------
    */

    private function resolveRecipientIds(
        array $validated
    ): array {

        $audienceType =
            $validated['audience_type'];


        /*
        |--------------------------------------------------------------------------
        | ALL USERS
        |--------------------------------------------------------------------------
        |
        | Teacher + Student
        |
        */

        if ($audienceType === 'all_users') {

            return User::query()
                ->whereIn(
                    'role',
                    [
                        'student',
                        'teacher',
                    ]
                )
                ->pluck('id')
                ->map(
                    fn ($id) => (int) $id
                )
                ->all();
        }


        /*
        |--------------------------------------------------------------------------
        | ALL STUDENTS
        |--------------------------------------------------------------------------
        */

        if (
            $audienceType
            ===
            'all_students'
        ) {

            return User::query()
                ->where(
                    'role',
                    'student'
                )
                ->pluck('id')
                ->map(
                    fn ($id) => (int) $id
                )
                ->all();
        }


        /*
        |--------------------------------------------------------------------------
        | ALL TEACHERS
        |--------------------------------------------------------------------------
        */

        if (
            $audienceType
            ===
            'all_teachers'
        ) {

            return User::query()
                ->where(
                    'role',
                    'teacher'
                )
                ->pluck('id')
                ->map(
                    fn ($id) => (int) $id
                )
                ->all();
        }


        /*
        |--------------------------------------------------------------------------
        | SINGLE USER
        |--------------------------------------------------------------------------
        */

        if (
            $audienceType
            ===
            'single_user'
        ) {

            $userId =
                $validated['single_user_id']
                ?? null;


            if (!$userId) {

                return [];
            }


            $user = User::query()

                ->where(
                    'id',
                    $userId
                )

                ->whereIn(
                    'role',
                    [
                        'student',
                        'teacher',
                    ]
                )

                ->first();


            return
                $user
                    ? [(int) $user->id]
                    : [];
        }


        /*
        |--------------------------------------------------------------------------
        | SELECTED USERS
        |--------------------------------------------------------------------------
        */

        if (
            $audienceType
            ===
            'selected_users'
        ) {

            $selectedIds =
                $validated[
                    'selected_user_ids'
                ]
                ?? [];


            if (empty($selectedIds)) {

                return [];
            }


            return User::query()

                ->whereIn(
                    'id',
                    $selectedIds
                )

                ->whereIn(
                    'role',
                    [
                        'student',
                        'teacher',
                    ]
                )

                ->pluck('id')

                ->map(
                    fn ($id) => (int) $id
                )

                ->all();
        }


        return [];
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN SECURITY
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
}