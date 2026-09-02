<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Notifications\PasswordChangedNotification;

class StudentProfileController extends Controller
{
    public function edit()
    {
        $student = Student::where('user_id', Auth::id())
            ->firstOrFail();

        return view(
            'student.profile.edit',
            compact('student')
        );
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $student = Student::where('user_id', $user->id)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            // ACCOUNT
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],


            // PROFILE PHOTO
            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],


            // STUDENT INFORMATION
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


        /*
        |--------------------------------------------------------------------------
        | UPDATE USER ACCOUNT
        |--------------------------------------------------------------------------
        */

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];


        $passwordChanged = false;

        if (!empty($validated['password'])) {
        
            $userData['password'] =
                Hash::make($validated['password']);
        
            $passwordChanged = true;
        }
        
        $user->update($userData);
        
        if ($passwordChanged) {
            $user->notify(
                new PasswordChangedNotification()
            );
        }

        $user->update($userData);


        /*
        |--------------------------------------------------------------------------
        | PROFILE PHOTO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_photo')) {

            /*
             * Delete old photo if it exists
             */
            if ($student->profile_photo) {

                Storage::disk('public')
                    ->delete($student->profile_photo);
            }


            /*
             * Store new photo
             */
            $student->profile_photo = $request
                ->file('profile_photo')
                ->store(
                    'students/profile-photos',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE STUDENT PROFILE
        |--------------------------------------------------------------------------
        */

        $student->birth_date =
            $validated['birth_date'] ?? null;

        $student->gender =
            $validated['gender'] ?? null;

        $student->city =
            $validated['city'] ?? null;

        $student->province =
            $validated['province'] ?? null;

        $student->country =
            $validated['country'] ?? 'Canada';

        $student->bio =
            $validated['bio'] ?? null;

        $student->experience_level =
            $validated['experience_level'] ?? null;

        $student->has_dance_experience =
            $validated['has_dance_experience'];


        /*
         * Save everything including profile_photo
         */
        $student->save();


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('student.profile.edit')
            ->with(
                'success',
                'Your profile has been updated successfully.'
            );
    }
}