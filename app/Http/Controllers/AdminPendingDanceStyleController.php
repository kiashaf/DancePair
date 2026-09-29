<?php

namespace App\Http\Controllers;

use App\Models\DanceStyle;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminPendingDanceStyleController extends Controller
{
    public function index(): View
    {
        $pendingDanceStyles = DanceStyle::query()
            ->with([
                'submittedByTeacher.user',
                'teachers',
            ])
            ->where('pending', true)
            ->orderByDesc('created_at')
            ->get();

        return view(
            'admin.pending-dance-styles.index',
            compact('pendingDanceStyles')
        );
    }


    public function approve(
        DanceStyle $danceStyle
    ): RedirectResponse {

        if (!$danceStyle->pending) {
            return redirect()
                ->route('admin.pending-dance-styles.index')
                ->with(
                    'error',
                    'This dance style is not pending.'
                );
        }

        $danceStyle->update([
            'active' => true,
            'pending' => false,
        ]);

        return redirect()
            ->route('admin.pending-dance-styles.index')
            ->with(
                'success',
                'Dance style approved successfully.'
            );
    }


    public function reject(
        DanceStyle $danceStyle
    ): RedirectResponse {

        if (!$danceStyle->pending) {
            return redirect()
                ->route('admin.pending-dance-styles.index')
                ->with(
                    'error',
                    'This dance style is not pending.'
                );
        }

        $danceStyle->teachers()->detach();

        $danceStyle->delete();

        return redirect()
            ->route('admin.pending-dance-styles.index')
            ->with(
                'success',
                'Dance style rejected successfully.'
            );
    }
}