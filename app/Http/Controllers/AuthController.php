<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\WelcomeToDancePairNotification;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW REGISTER
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('register');
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role' => [
                'required',
                'in:student,teacher',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CREATE ACCOUNT
        |--------------------------------------------------------------------------
        */

        $user = DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | CREATE USER
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(
                    $validated['password']
                ),
                'role' => $validated['role'],

                // New accounts are active by default.
                'active' => true,
            ]);


            /*
            |--------------------------------------------------------------------------
            | CREATE TEACHER PROFILE
            |--------------------------------------------------------------------------
            */

            if ($user->role === 'teacher') {

                Teacher::create([
                    'user_id' => $user->id,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | CREATE STUDENT PROFILE
            |--------------------------------------------------------------------------
            */

            if ($user->role === 'student') {

                Student::create([
                    'user_id' => $user->id,
                ]);
            }


            return $user;
        });


        /*
        |--------------------------------------------------------------------------
        | LOGIN NEW USER
        |--------------------------------------------------------------------------
        */
        $user->notify(
            new WelcomeToDancePairNotification()
        );
        Auth::login($user);

        $request
            ->session()
            ->regenerate();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT BY ROLE
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'teacher') {

            return redirect()
                ->route('teacher.dashboard')
                ->with(
                    'success',
                    'Welcome to DancePair! Your teacher account has been created successfully.'
                );
        }


        if ($user->role === 'student') {

            return redirect()
                ->route('student.dashboard')
                ->with(
                    'success',
                    'Welcome to DancePair! Your student account has been created successfully.'
                );
        }


        return redirect('/');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('login');
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATE
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt($credentials)) {

            return back()
                ->withErrors([
                    'email' =>
                        'Email or password is incorrect.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | GET AUTHENTICATED USER
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | BLOCK INACTIVE ACCOUNT
        |--------------------------------------------------------------------------
        |
        | The admin can deactivate a Teacher or Student without deleting
        | their account. An inactive user cannot log in.
        |
        */

        if (!$user->active) {

            Auth::logout();

            $request
                ->session()
                ->invalidate();

            $request
                ->session()
                ->regenerateToken();


            return back()
                ->withErrors([
                    'email' =>
                        'Your DancePair account is currently inactive. Please contact support.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN SUCCESS
        |--------------------------------------------------------------------------
        */

        $request
            ->session()
            ->regenerate();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT BY ROLE
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()
                ->intended(
                    route('admin.dashboard')
                );
        }


        if ($user->role === 'teacher') {

            return redirect()
                ->intended(
                    route('teacher.dashboard')
                );
        }


        if ($user->role === 'student') {

            return redirect()
                ->intended(
                    route('student.dashboard')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return redirect('/');
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();


        return redirect()
            ->route('login');
    }
}