<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\City;
use App\Models\DanceStyle;
use App\Models\Payment;
use App\Models\Province;
use App\Models\Review;
use App\Models\Student;
use App\Models\Teacher;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminTeacherController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $teachers = Teacher::with([
            'user',
            'danceStyles',
        ])
            ->whereHas('user', function ($query) {
                $query->where('role', 'teacher');
            })
            ->latest()
            ->get();

        return view(
            'admin.teachers.index',
            compact('teachers')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Teacher $teacher)
    {
        $teacher->load([
            'user',
            'danceStyles',
        ]);

        $danceStyles = DanceStyle::where(
                'active',
                true
            )
            ->orderBy('name')
            ->get();


        $provinces = Province::orderBy('name')
            ->get();


        $selectedProvince = Province::where(
            'name',
            $teacher->province
        )->first();


        $cities = collect();

        if ($selectedProvince) {
            $cities = City::where(
                    'province_id',
                    $selectedProvince->id
                )
                ->orderBy('name')
                ->get();
        }


        return view(
            'admin.teachers.edit',
            compact(
                'teacher',
                'danceStyles',
                'provinces',
                'selectedProvince',
                'cities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Teacher $teacher
    ) {
        $teacher->load('user');

        $user = $teacher->user;


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

            'role' => [
                'required',
                'in:teacher,student',
            ],

            'active' => [
                'required',
                'boolean',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],


            /*
            |--------------------------------------------------------------------------
            | ADMIN TEACHER STATUS
            |--------------------------------------------------------------------------
            */

            'verified' => [
                'required',
                'boolean',
            ],


            /*
            |--------------------------------------------------------------------------
            | PROFILE MEDIA
            |--------------------------------------------------------------------------
            */

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'intro_video' => [
                'nullable',
                'file',
                'mimes:mp4,mov,webm',
                'max:51200',
            ],


            /*
            |--------------------------------------------------------------------------
            | TEACHER PROFILE
            |--------------------------------------------------------------------------
            */

            'bio' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'experience_years' => [
                'nullable',
                'integer',
                'min:0',
                'max:80',
            ],

            'hourly_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'province' => [
                'nullable',
                'integer',
                'exists:provinces,id',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'country' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | DANCE STYLES
            |--------------------------------------------------------------------------
            */

            'dance_styles' => [
                'nullable',
                'array',
            ],

            'dance_styles.*' => [
                'integer',
                'exists:dance_styles,id',
            ],

            'dance_rates' => [
                'nullable',
                'array',
            ],

            'dance_rates.*' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999.99',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | BUILD DANCE STYLE DATA BEFORE TRANSACTION
        |--------------------------------------------------------------------------
        */

        $selectedDanceStyles =
            $validated['dance_styles']
            ?? [];

        $danceRates =
            $validated['dance_rates']
            ?? [];

        $syncData = [];


        foreach (
            $selectedDanceStyles as $styleId
        ) {
            $rate =
                $danceRates[$styleId]
                ?? null;

            if (
                $rate === null
                ||
                $rate === ''
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'dance_rates.' . $styleId =>
                            'Please enter an hourly rate for every selected dance style.',
                    ]);
            }


            $syncData[$styleId] = [
                'hourly_rate' => $rate,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE EVERYTHING
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $request,
                $validated,
                $teacher,
                $user,
                $syncData
            ) {

                /*
                |--------------------------------------------------------------------------
                | USER ACCOUNT
                |--------------------------------------------------------------------------
                */

                $user->name =
                    $validated['name'];

                $user->email =
                    $validated['email'];

                $user->role =
                    $validated['role'];

                $user->active =
                    (bool) $validated['active'];


                if (
                    !empty(
                        $validated['password']
                    )
                ) {
                    $user->password =
                        Hash::make(
                            $validated['password']
                        );
                }

                $user->save();


                /*
                |--------------------------------------------------------------------------
                | PROVINCE
                |--------------------------------------------------------------------------
                */

                $provinceName = null;

                if (
                    !empty(
                        $validated['province']
                    )
                ) {
                    $province =
                        Province::find(
                            $validated['province']
                        );

                    $provinceName =
                        $province?->name;
                }


                /*
                |--------------------------------------------------------------------------
                | PROFILE
                |--------------------------------------------------------------------------
                */

                $teacher->bio =
                    $validated['bio']
                    ?? null;

                $teacher->experience_years =
                    $validated['experience_years']
                    ?? 0;

                $teacher->hourly_rate =
                    $validated['hourly_rate']
                    ?? null;

                $teacher->province =
                    $provinceName;

                $teacher->city =
                    $validated['city']
                    ?? null;

                $teacher->country =
                    $validated['country']
                    ?? 'Canada';

                $teacher->currency =
                    'CAD';

                $teacher->verified =
                    (bool) $validated['verified'];


                /*
                |--------------------------------------------------------------------------
                | PROFILE PHOTO
                |--------------------------------------------------------------------------
                */

                if (
                    $request->hasFile(
                        'profile_photo'
                    )
                ) {
                    if (
                        $teacher->profile_photo
                    ) {
                        Storage::disk(
                            'public'
                        )->delete(
                            $teacher->profile_photo
                        );
                    }

                    $teacher->profile_photo =
                        $request
                            ->file(
                                'profile_photo'
                            )
                            ->store(
                                'teachers/profile-photos',
                                'public'
                            );
                }


                /*
                |--------------------------------------------------------------------------
                | INTRO VIDEO
                |--------------------------------------------------------------------------
                */

                if (
                    $request->hasFile(
                        'intro_video'
                    )
                ) {
                    if (
                        $teacher->intro_video
                    ) {
                        Storage::disk(
                            'public'
                        )->delete(
                            $teacher->intro_video
                        );
                    }

                    $teacher->intro_video =
                        $request
                            ->file(
                                'intro_video'
                            )
                            ->store(
                                'teachers/intro-videos',
                                'public'
                            );
                }


                $teacher->save();


                /*
                |--------------------------------------------------------------------------
                | DANCE STYLE RATES
                |--------------------------------------------------------------------------
                */

                $teacher
                    ->danceStyles()
                    ->sync(
                        $syncData
                    );


                /*
                |--------------------------------------------------------------------------
                | CHANGE TO STUDENT IF ADMIN CHANGES ROLE
                |--------------------------------------------------------------------------
                */

                if (
                    $validated['role']
                    ===
                    'student'
                ) {
                    Student::firstOrCreate([
                        'user_id' =>
                            $user->id,
                    ]);
                }
            }
        );


        if (
            $validated['role']
            ===
            'student'
        ) {
            return redirect()
                ->route(
                    'admin.students'
                )
                ->with(
                    'success',
                    'Account changed to Student successfully.'
                );
        }


        return back()->with(
            'success',
            'Teacher updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Teacher $teacher
    ) {
        $teacher->load('user');

        $user = $teacher->user;


        if (!$user) {
            return redirect()
                ->route(
                    'admin.teachers'
                )
                ->with(
                    'error',
                    'Teacher account not found.'
                );
        }


        DB::transaction(
            function () use (
                $teacher,
                $user
            ) {

                $bookingIds =
                    Booking::where(
                        'teacher_id',
                        $teacher->id
                    )->pluck('id');


                Payment::where(
                    'teacher_id',
                    $teacher->id
                )->delete();


                Review::where(
                    'teacher_id',
                    $teacher->id
                )->delete();


                if (
                    $bookingIds->isNotEmpty()
                ) {
                    Review::whereIn(
                        'booking_id',
                        $bookingIds
                    )->delete();
                }


                Booking::where(
                    'teacher_id',
                    $teacher->id
                )->delete();


                DB::table(
                    'teacher_availabilities'
                )
                    ->where(
                        'teacher_id',
                        $teacher->id
                    )
                    ->delete();


                $teacher
                    ->danceStyles()
                    ->detach();


                if (
                    $teacher->profile_photo
                ) {
                    Storage::disk(
                        'public'
                    )->delete(
                        $teacher->profile_photo
                    );
                }


                if (
                    $teacher->intro_video
                ) {
                    Storage::disk(
                        'public'
                    )->delete(
                        $teacher->intro_video
                    );
                }


                DB::table(
                    'notifications'
                )
                    ->where(
                        'notifiable_type',
                        get_class($user)
                    )
                    ->where(
                        'notifiable_id',
                        $user->id
                    )
                    ->delete();


                Student::where(
                    'user_id',
                    $user->id
                )->delete();


                $teacher->delete();

                $user->delete();
            }
        );


        return redirect()
            ->route(
                'admin.teachers'
            )
            ->with(
                'success',
                'Teacher account deleted successfully.'
            );
    }
}