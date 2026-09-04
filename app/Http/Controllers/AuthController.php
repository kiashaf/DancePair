<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;

use App\Notifications\WelcomeToDancePairNotification;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use Illuminate\Support\Str;


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
        | CREATE USER + PROFILE
        |--------------------------------------------------------------------------
        */

        $user = DB::transaction(
            function () use ($validated) {

                $user = User::create([

                    'name' =>
                        $validated['name'],

                    'email' =>
                        strtolower(
                            trim(
                                $validated['email']
                            )
                        ),

                    'password' =>
                        Hash::make(
                            $validated['password']
                        ),

                    'role' =>
                        $validated['role'],

                    'active' =>
                        true,
                ]);


                if ($user->role === 'teacher') {

                    Teacher::create([
                        'user_id' =>
                            $user->id,
                    ]);
                }


                if ($user->role === 'student') {

                    Student::create([
                        'user_id' =>
                            $user->id,
                    ]);
                }


                return $user;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | LOGIN NEW USER
        |--------------------------------------------------------------------------
        */

        Auth::login($user);


        $request
            ->session()
            ->regenerate();


        /*
        |--------------------------------------------------------------------------
        | SEND EMAIL VERIFICATION
        |--------------------------------------------------------------------------
        |
        | We DO NOT send Welcome email yet.
        |
        | First:
        | User must prove that the email belongs to them.
        |
        */

        $user
            ->sendEmailVerificationNotification();


        /*
        |--------------------------------------------------------------------------
        | VERIFY EMAIL SCREEN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'verification.notice'
            )
            ->with(
                'status',
                'verification-link-sent'
            );
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
        $credentials =
            $request->validate([

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

        if (
            !Auth::attempt(
                $credentials
            )
        ) {

            return back()
                ->withErrors([

                    'email' =>
                        app()->getLocale() === 'fr'
                            ? 'L’adresse courriel ou le mot de passe est incorrect.'
                            : 'Email or password is incorrect.',

                ])
                ->onlyInput(
                    'email'
                );
        }


        $user =
            Auth::user();


        /*
        |--------------------------------------------------------------------------
        | BLOCK INACTIVE ACCOUNT
        |--------------------------------------------------------------------------
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
                        app()->getLocale() === 'fr'
                            ? 'Votre compte DancePair est actuellement inactif. Veuillez contacter le support.'
                            : 'Your DancePair account is currently inactive. Please contact support.',

                ])
                ->onlyInput(
                    'email'
                );
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
        | ADMIN
        |--------------------------------------------------------------------------
        |
        | Admin account is managed internally by DancePair.
        |
        | If an old admin account existed before verification was added,
        | we consider it trusted.
        |
        */

        if (
            $user->role === 'admin'
            &&
            !$user->hasVerifiedEmail()
        ) {

            $user
                ->markEmailAsVerified();
        }


        /*
        |--------------------------------------------------------------------------
        | REQUIRE EMAIL VERIFICATION
        |--------------------------------------------------------------------------
        */

        if (
            $user->role !== 'admin'
            &&
            !$user->hasVerifiedEmail()
        ) {

            return redirect()
                ->route(
                    'verification.notice'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECT BY ROLE
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()
                ->intended(
                    route(
                        'admin.dashboard'
                    )
                );
        }


        if ($user->role === 'teacher') {

            return redirect()
                ->intended(
                    route(
                        'teacher.dashboard'
                    )
                );
        }


        if ($user->role === 'student') {

            return redirect()
                ->intended(
                    route(
                        'student.dashboard'
                    )
                );
        }


        return redirect('/');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW VERIFY EMAIL
    |--------------------------------------------------------------------------
    */

    public function showVerifyEmail(
        Request $request
    ) {

        $user =
            $request->user();


        if (
            $user
            &&
            $user->hasVerifiedEmail()
        ) {

            return $this
                ->redirectByRole(
                    $user
                );
        }


        return view(
            'verify-email'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESEND VERIFICATION EMAIL
    |--------------------------------------------------------------------------
    */

    public function resendVerificationEmail(
        Request $request
    ) {

        $user =
            $request->user();


        if (
            $user->hasVerifiedEmail()
        ) {

            return $this
                ->redirectByRole(
                    $user
                );
        }


        $user
            ->sendEmailVerificationNotification();


        return back()
            ->with(
                'status',
                'verification-link-sent'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY EMAIL
    |--------------------------------------------------------------------------
    */

    public function verifyEmail(
        EmailVerificationRequest $request
    ) {

        $user =
            $request->user();


        $alreadyVerified =
            $user->hasVerifiedEmail();


        /*
        |--------------------------------------------------------------------------
        | VERIFY
        |--------------------------------------------------------------------------
        */

        if (!$alreadyVerified) {

            $request
                ->fulfill();


            /*
            |--------------------------------------------------------------------------
            | WELCOME EMAIL
            |--------------------------------------------------------------------------
            |
            | Welcome email is sent ONLY after email ownership
            | has been successfully verified.
            |
            */

            try {

                $user->notify(
                    new WelcomeToDancePairNotification()
                );

            } catch (\Throwable $exception) {

                /*
                 * Verification must remain successful even if
                 * the Welcome email temporarily fails.
                 */

                report(
                    $exception
                );
            }
        }


        return $this
            ->redirectByRole(
                $user
            )
            ->with(
                'success',

                app()->getLocale() === 'fr'
                    ? 'Votre adresse courriel a été vérifiée avec succès.'
                    : 'Your email address has been verified successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showForgotPassword()
    {
        return view(
            'forgot-password'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEND PASSWORD RESET LINK
    |--------------------------------------------------------------------------
    */

    public function sendPasswordResetLink(
        Request $request
    ) {

        $validated =
            $request->validate([

                'email' => [
                    'required',
                    'email',
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | SEND LINK
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | We intentionally ignore the result publicly.
        |
        | This prevents someone from discovering whether
        | an email address has a DancePair account.
        |
        */

        Password::sendResetLink([
            'email' =>
                strtolower(
                    trim(
                        $validated['email']
                    )
                ),
        ]);


        return back()
            ->with(
                'status',

                app()->getLocale() === 'fr'

                    ? 'Si un compte existe pour cette adresse courriel, un lien de réinitialisation a été envoyé.'

                    : 'If an account exists for this email address, a password reset link has been sent.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function showResetPassword(
        Request $request,
        string $token
    ) {

        return view(
            'reset-password',
            [

                'token' =>
                    $token,

                'email' =>
                    $request->query(
                        'email'
                    ),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function resetPassword(
        Request $request
    ) {

        $validated =
            $request->validate([

                'token' => [
                    'required',
                ],

                'email' => [
                    'required',
                    'email',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ]);


        $status =
            Password::reset(

                $validated,

                function (
                    User $user,
                    string $password
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | SAVE NEW PASSWORD
                    |--------------------------------------------------------------------------
                    |
                    | User model already has:
                    |
                    | 'password' => 'hashed'
                    |
                    */

                    $user->password =
                        $password;


                    /*
                     * Invalidate old remember-me tokens.
                     */

                    $user->setRememberToken(
                        Str::random(60)
                    );


                    $user->save();


                    event(
                        new PasswordReset(
                            $user
                        )
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        if (
            $status
            ===
            Password::PASSWORD_RESET
        ) {

            return redirect()
                ->route(
                    'login'
                )
                ->with(
                    'status',

                    app()->getLocale() === 'fr'

                        ? 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant vous connecter.'

                        : 'Your password has been reset. You can now log in.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | INVALID / EXPIRED TOKEN
        |--------------------------------------------------------------------------
        */

        return back()
            ->withErrors([

                'email' =>
                    app()->getLocale() === 'fr'

                        ? 'Ce lien de réinitialisation est invalide ou a expiré.'

                        : 'This password reset link is invalid or has expired.',

            ])
            ->withInput(
                $request->only(
                    'email'
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(
        Request $request
    ) {

        Auth::logout();


        $request
            ->session()
            ->invalidate();


        $request
            ->session()
            ->regenerateToken();


        return redirect()
            ->route(
                'login'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REDIRECT BY ROLE
    |--------------------------------------------------------------------------
    */

    private function redirectByRole(
        User $user
    ) {

        if ($user->role === 'admin') {

            return redirect()
                ->route(
                    'admin.dashboard'
                );
        }


        if ($user->role === 'teacher') {

            return redirect()
                ->route(
                    'teacher.dashboard'
                );
        }


        if ($user->role === 'student') {

            return redirect()
                ->route(
                    'student.dashboard'
                );
        }


        return redirect('/');
    }
}