<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class TeacherStripeConnectController extends Controller
{
    public function connect()
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $stripe = new StripeClient(
            env('STRIPE_SECRET')
        );

        if (!$teacher->stripe_account_id) {

            $account = $stripe->v2->core->accounts->create([
                'contact_email' => $teacher->user?->email,

                'display_name' => $teacher->user?->name,

                'identity' => [
                    'country' => 'CA',
                ],
                'dashboard' => 'express',

'defaults' => [
    'responsibilities' => [
        'fees_collector' => 'application',
        'losses_collector' => 'application',
    ],
],

                'configuration' => [
                    'recipient' => [
                        'capabilities' => [
                            'stripe_balance' => [
                                'stripe_transfers' => [
                                    'requested' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            $teacher->update([
                'stripe_account_id' => $account->id,
            ]);
        }

        $accountLink = $stripe->v2->core->accountLinks->create([
            'account' => $teacher->stripe_account_id,

            'use_case' => [
                'type' => 'account_onboarding',

                'account_onboarding' => [
                    'configurations' => [
                        'recipient',
                    ],

                    'refresh_url' => route(
                        'teacher.stripe.refresh'
                    ),

                    'return_url' => route(
                        'teacher.stripe.return'
                    ),

                    'collection_options' => [
                        'fields' => 'eventually_due',
                        'future_requirements' => 'include',
                    ],
                ],
            ],
        ]);

        return redirect()->away(
            $accountLink->url
        );
    }


    public function refresh()
    {
        return redirect()
            ->route('teacher.stripe.connect');
    }


    public function return()
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $stripe = new StripeClient(
            env('STRIPE_SECRET')
        );

        $account = $stripe->v2->core->accounts->retrieve(
            $teacher->stripe_account_id,
            [
                'include' => [
                    'requirements',
                    'configuration.recipient',
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | STRIPE TRANSFERS STATUS
        |--------------------------------------------------------------------------
        */

        $transfersEnabled =
            (
                $account
                    ->configuration
                    ?->recipient
                    ?->capabilities
                    ?->stripe_balance
                    ?->stripe_transfers
                    ?->status
                === 'active'
            );

        /*
        |--------------------------------------------------------------------------
        | PAYOUTS STATUS
        |--------------------------------------------------------------------------
        */

        $payoutsEnabled =
            (
                $account
                    ->configuration
                    ?->recipient
                    ?->capabilities
                    ?->stripe_balance
                    ?->payouts
                    ?->status
                === 'active'
            );

        /*
        |--------------------------------------------------------------------------
        | READY
        |--------------------------------------------------------------------------
        */

        $ready =
            $transfersEnabled
            &&
            $payoutsEnabled;

        $teacher->update([
            'stripe_onboarding_complete' => $ready,
            'stripe_payouts_enabled' => $ready,
        ]);

        if ($ready) {

            return redirect()
                ->route('teacher.profile.edit')
                ->with(
                    'success',
                    'Your payout account is connected and ready to receive payments.'
                );
        }

        return redirect()
            ->route('teacher.profile.edit')
            ->with(
                'warning',
                'Your Stripe account was created, but Stripe still requires additional information before payouts can be enabled.'
            );
    }
}