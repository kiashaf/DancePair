<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminStudentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $students = Student::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->latest()
            ->get();

        return view(
            'admin.students.index',
            compact('students')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Student $student)
    {
        $student->load('user');

        return view(
            'admin.students.edit',
            compact('student')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Student $student
    ) {
        $student->load('user');

        $user = $student->user;

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | ACCOUNT
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
                'in:student,teacher',
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
            | PROFILE PHOTO
            |--------------------------------------------------------------------------
            */

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],


            /*
            |--------------------------------------------------------------------------
            | STUDENT PROFILE
            |--------------------------------------------------------------------------
            */

            'birth_date' => [
                'nullable',
                'date',
            ],

            'gender' => [
                'nullable',
                'in:male,female,other,prefer_not_to_say',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'province' => [
                'nullable',
                'string',
                'max:255',
            ],

            'country' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bio' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'experience_level' => [
                'nullable',
                'in:beginner,intermediate,advanced',
            ],

            'has_dance_experience' => [
                'required',
                'boolean',
            ],
        ]);


        DB::transaction(
            function () use (
                $request,
                $validated,
                $student,
                $user
            ) {

                /*
                |--------------------------------------------------------------------------
                | USER
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
                | PROFILE PHOTO
                |--------------------------------------------------------------------------
                */

                if (
                    $request->hasFile(
                        'profile_photo'
                    )
                ) {
                    if (
                        $student->profile_photo
                    ) {
                        Storage::disk(
                            'public'
                        )->delete(
                            $student->profile_photo
                        );
                    }

                    $student->profile_photo =
                        $request
                            ->file(
                                'profile_photo'
                            )
                            ->store(
                                'students/profile-photos',
                                'public'
                            );
                }


                /*
                |--------------------------------------------------------------------------
                | STUDENT PROFILE
                |--------------------------------------------------------------------------
                */

                $student->birth_date =
                    $validated['birth_date']
                    ?? null;

                $student->gender =
                    $validated['gender']
                    ?? null;

                $student->city =
                    $validated['city']
                    ?? null;

                $student->province =
                    $validated['province']
                    ?? null;

                $student->country =
                    $validated['country']
                    ?? 'Canada';

                $student->bio =
                    $validated['bio']
                    ?? null;

                $student->experience_level =
                    $validated['experience_level']
                    ?? null;

                $student->has_dance_experience =
                    (bool)
                    $validated['has_dance_experience'];

                $student->save();


                /*
                |--------------------------------------------------------------------------
                | CHANGE TO TEACHER
                |--------------------------------------------------------------------------
                */

                if (
                    $validated['role']
                    ===
                    'teacher'
                ) {
                    Teacher::firstOrCreate([
                        'user_id' =>
                            $user->id,
                    ]);
                }
            }
        );


        if (
            $validated['role']
            ===
            'teacher'
        ) {
            return redirect()
                ->route(
                    'admin.teachers'
                )
                ->with(
                    'success',
                    'Account changed to Teacher successfully.'
                );
        }


        return back()->with(
            'success',
            'Student updated successfully.'
        );
    }
}