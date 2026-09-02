<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\CommissionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    public function edit()
    {
        $admin = auth()->user();

        $platformCommissionPercent = (float) Setting::getValue(
            'platform_commission_percent',
            15
        );

        /*
        |--------------------------------------------------------------------------
        | COMMISSION HISTORY
        |--------------------------------------------------------------------------
        */

        $commissionHistory = CommissionHistory::with('changedBy')
            ->latest()
            ->take(50)
            ->get();


        return view(
            'admin.settings',
            compact(
                'admin',
                'platformCommissionPercent',
                'commissionHistory'
            )
        );
    }


    public function update(Request $request)
    {
        $admin = auth()->user();

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
                'unique:users,email,' . $admin->id,
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
            ],

            'platform_commission_percent' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE ADMIN ACCOUNT
        |--------------------------------------------------------------------------
        */

        $admin->name = $validated['name'];

        $admin->email = $validated['email'];


        if (!empty($validated['password'])) {

            $admin->password = Hash::make(
                $validated['password']
            );
        }


        $admin->save();


        /*
        |--------------------------------------------------------------------------
        | CURRENT COMMISSION BEFORE CHANGE
        |--------------------------------------------------------------------------
        */

        $oldCommissionPercent = (float) Setting::getValue(
            'platform_commission_percent',
            15
        );


        /*
        |--------------------------------------------------------------------------
        | NEW COMMISSION
        |--------------------------------------------------------------------------
        */

        $newCommissionPercent = (float) $validated[
            'platform_commission_percent'
        ];


        /*
        |--------------------------------------------------------------------------
        | ONLY CREATE HISTORY IF COMMISSION ACTUALLY CHANGED
        |--------------------------------------------------------------------------
        */

        if (
            round($oldCommissionPercent, 2)
            !==
            round($newCommissionPercent, 2)
        ) {

            CommissionHistory::create([

                'old_percentage' =>
                    $oldCommissionPercent,

                'new_percentage' =>
                    $newCommissionPercent,

                'changed_by' =>
                    $admin->id,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE DANSEPAIR COMMISSION
        |--------------------------------------------------------------------------
        */

        Setting::setValue(
            'platform_commission_percent',
            $newCommissionPercent
        );


        return back()->with(
            'success',
            'Admin settings and DansePair commission updated successfully.'
        );
    }
}