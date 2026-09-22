<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileCreationController extends Controller
{
    public function createTeacher(Request $request): RedirectResponse
    {
        return $this->createProfile($request, 'student');
    }

    public function createStudent(Request $request): RedirectResponse
    {
        return $this->createProfile($request, 'teacher');
    }

    private function createProfile(Request $request, string $sourceRole): RedirectResponse
    {
        $user = $request->user();

        abort_unless(
            $user && $user->active && $user->role === $sourceRole,
            403
        );

        $sourceModel = $sourceRole === 'student' ? Student::class : Teacher::class;
        $targetModel = $sourceRole === 'student' ? Teacher::class : Student::class;
        $target = $sourceRole === 'student' ? 'teacher' : 'student';

        $profile = DB::transaction(function () use ($user, $sourceRole, $sourceModel, $targetModel) {
            // Serialize profile creation for this account.
            $account = User::query()
                ->whereKey($user->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $account->active && $account->role === $sourceRole,
                403
            );

            abort_unless(
                $sourceModel::where('user_id', $account->id)->exists(),
                403
            );

            // Reuse the existing registration logic and model defaults.
            // Never overwrite an existing profile or change users.role.
            return $targetModel::firstOrCreate([
                'user_id' => $account->id,
            ]);
        });

        return redirect()
            ->route($target . '.profile.edit')
            ->with(
                'success',
                __(
                    $profile->wasRecentlyCreated
                        ? 'profile_access.' . $target . '_created'
                        : 'profile_access.profile_exists'
                )
            );
    }
}