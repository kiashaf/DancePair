<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminImpersonationController extends Controller
{
    public function start(
        Request $request,
        User $user
    ) {
        $admin = Auth::user();

        abort_unless(
            $admin,
            403
        );

        $as = $request->input('as');

        abort_unless(
            in_array(
                $as,
                [
                    'student',
                    'teacher',
                ],
                true
            ),
            403
        );

        if (
            $as === 'student'
            && !$user->student
        ) {
            abort(404);
        }

        if (
            $as === 'teacher'
            && !$user->teacher
        ) {
            abort(404);
        }

        session([
            'impersonator_admin_id' => $admin->id,
            'impersonating_as' => $as,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        if ($as === 'student') {
            return redirect()
                ->route('student.dashboard');
        }

        return redirect()
            ->route('teacher.dashboard');
    }


    public function stop(Request $request)
    {
        $adminId = session(
            'impersonator_admin_id'
        );

        abort_unless(
            $adminId,
            403
        );

        $admin = User::findOrFail(
            $adminId
        );

        Auth::login($admin);

        $request->session()->forget([
            'impersonator_admin_id',
            'impersonating_as',
        ]);

        $request->session()->regenerate();

        return redirect()
            ->route('admin.dashboard');
    }
}