<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentPackage;
use App\Models\Teacher;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Stripe\Checkout\Session;
use Stripe\Stripe;

class StudentPackageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PACKAGE CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function checkout(
        Request $request,
        Teacher $teacher,
        Promotion $promotion
    )
    {
        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        $student = Student::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | VALIDATE REQUEST
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'dance_style_id' => [
                'required',
                'integer',
                'exists:dance_styles,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | PROMOTION SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $promotion->teacher_id
                ===
            (int) $teacher->id,
            403
        );

        abort_unless(
            $promotion->type === 'package_discount',
            404
        );


        /*
        |--------------------------------------------------------------------------
        | PROMOTION MUST BE ACTIVE
        |--------------------------------------------------------------------------
        */

        if (!$promotion->is_active) {
            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce forfait promotionnel n’est plus actif.'
                    : 'This package promotion is no longer active.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PROMOTION START DATE
        |--------------------------------------------------------------------------
        */

        if (
            $promotion->starts_at
            &&
            now()->lt($promotion->starts_at)
        ) {
            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce forfait promotionnel n’est pas encore disponible.'
                    : 'This package promotion is not available yet.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PROMOTION END DATE
        |--------------------------------------------------------------------------
        */

        if (
            $promotion->ends_at
            &&
            now()->gt($promotion->ends_at)
        ) {
            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce forfait promotionnel est expiré.'
                    : 'This package promotion has expired.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PACKAGE SIZE
        |--------------------------------------------------------------------------
        */

        $packageSize = (int) $promotion->package_size;

        if (
            !in_array(
                $packageSize,
                [5, 10, 20],
                true
            )
        ) {
            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'La taille de ce forfait est invalide.'
                    : 'This package size is invalid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DISCOUNT PERCENT
        |--------------------------------------------------------------------------
        */

        $discountPercent = (float) (
            $promotion->discount_percent
            ?? 0
        );

        if (
            $discountPercent <= 0
            ||
            $discountPercent >= 100
        ) {
            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Le rabais de ce forfait est invalide.'
                    : 'This package discount is invalid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD TEACHER DANCE STYLE
        |--------------------------------------------------------------------------
        */

        $teacher->load([
            'user',
            'danceStyles',
        ]);

        $danceStyle = $teacher
            ->danceStyles
            ->firstWhere(
                'id',
                (int) $validated[
                    'dance_style_id'
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | TEACHER MUST TEACH THIS DANCE STYLE
        |--------------------------------------------------------------------------
        */

        if (!$danceStyle) {
            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Cet instructeur n’offre pas ce style de danse.'
                    : 'This instructor does not offer this dance style.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SESSION PRICE
        |--------------------------------------------------------------------------
        */

        $sessionPrice = round(
            (float) (
                $danceStyle
                    ?->pivot
                    ?->hourly_rate
                ?? 0
            ),
            2
        );

        if ($sessionPrice <= 0) {
            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Le prix de ce style de danse est invalide.'
                    : 'The price for this dance style is invalid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ORIGINAL PACKAGE AMOUNT
        |--------------------------------------------------------------------------
        */

        $originalAmount = round(
            $sessionPrice * $packageSize,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | DISCOUNT
        |--------------------------------------------------------------------------
        |
        | The discount comes from the Teacher's share.
        |
        */

        $discountAmount = round(
            $originalAmount
                * ($discountPercent / 100),
            2
        );

        $finalAmount = round(
            $originalAmount
                - $discountAmount,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | DANCEPAIR COMMISSION
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | DancePair commission is calculated on the ORIGINAL amount.
        | The Teacher pays for the package discount.
        |
        */

        $commissionPercent = (float) Setting::getValue(
            'platform_commission_percent',
            0
        );

        $platformFee = round(
            $originalAmount
                * ($commissionPercent / 100),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | TEACHER AMOUNT
        |--------------------------------------------------------------------------
        */

        $teacherAmount = round(
            $finalAmount
                - $platformFee,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | SAFETY
        |--------------------------------------------------------------------------
        |
        | Stripe application fee cannot be greater than the amount
        | charged to the Student.
        |
        */

        if (
            $finalAmount <= 0
            ||
            $platformFee > $finalAmount
            ||
            $teacherAmount < 0
        ) {
            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce rabais est trop élevé pour ce forfait.'
                    : 'The discount is too high for this package.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TEACHER STRIPE ACCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            !$teacher->stripe_account_id
            ||
            !$teacher->stripe_payouts_enabled
        ) {
            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Cet instructeur n’est pas encore prêt à recevoir des paiements.'
                    : 'This instructor is not ready to receive payments yet.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE PENDING STUDENT PACKAGE
        |--------------------------------------------------------------------------
        */

        $studentPackage = StudentPackage::create([
            'student_id' =>
                $student->id,

            'teacher_id' =>
                $teacher->id,

            'promotion_id' =>
                $promotion->id,

            'dance_style_id' =>
                $danceStyle->id,

            'package_size' =>
                $packageSize,

            'sessions_used' =>
                0,

            'sessions_remaining' =>
                $packageSize,

            'session_price' =>
                $sessionPrice,

            'original_amount' =>
                $originalAmount,

            'discount_percent' =>
                $discountPercent,

            'discount_amount' =>
                $discountAmount,

            'final_amount' =>
                $finalAmount,

            'currency' =>
                'CAD',

            'status' =>
                'pending',
        ]);


        /*
        |--------------------------------------------------------------------------
        | STRIPE
        |--------------------------------------------------------------------------
        */

        Stripe::setApiKey(
            env('STRIPE_SECRET')
        );


        /*
        |--------------------------------------------------------------------------
        | DANCEPAIR COMMISSION IN CENTS
        |--------------------------------------------------------------------------
        */

        $platformFeeCents = (int) round(
            $platformFee * 100
        );


        /*
        |--------------------------------------------------------------------------
        | PAYMENT INTENT
        |--------------------------------------------------------------------------
        */

        $paymentIntentData = [
            'transfer_data' => [
                'destination' =>
                    $teacher->stripe_account_id,
            ],

            'metadata' => [
                'student_package_id' =>
                    (string) $studentPackage->id,

                'student_id' =>
                    (string) $student->id,

                'teacher_id' =>
                    (string) $teacher->id,

                'promotion_id' =>
                    (string) $promotion->id,

                'dance_style_id' =>
                    (string) $danceStyle->id,

                'package_size' =>
                    (string) $packageSize,

                'original_amount' =>
                    (string) $originalAmount,

                'final_amount' =>
                    (string) $finalAmount,

                'platform_fee' =>
                    (string) $platformFee,

                'teacher_amount' =>
                    (string) $teacherAmount,
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | APPLICATION FEE
        |--------------------------------------------------------------------------
        */

        if ($platformFeeCents > 0) {
            $paymentIntentData[
                'application_fee_amount'
            ] = $platformFeeCents;
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE STRIPE CHECKOUT SESSION
        |--------------------------------------------------------------------------
        */

        $session = Session::create([
            'mode' => 'payment',

            'managed_payments' => [
                'enabled' => false,
            ],

            'payment_intent_data' =>
                $paymentIntentData,

            'line_items' => [
                [
                    'price_data' => [
                        'currency' =>
                            'cad',

                        'unit_amount' =>
                            (int) round(
                                $finalAmount * 100
                            ),

                        'product_data' => [
                            'name' =>
                                $packageSize
                                . ' Sessions Package - '
                                . (
                                    $danceStyle->name
                                    ?? 'Dance'
                                ),

                            'description' =>
                                (
                                    $discountPercent
                                )
                                . '% Off • '
                                . (
                                    $teacher
                                        ->user
                                        ->name
                                    ?? 'Instructor'
                                ),
                        ],
                    ],

                    'quantity' => 1,
                ],
            ],

            'metadata' => [
                'student_package_id' =>
                    (string) $studentPackage->id,

                'student_id' =>
                    (string) $student->id,

                'teacher_id' =>
                    (string) $teacher->id,

                'promotion_id' =>
                    (string) $promotion->id,

                'dance_style_id' =>
                    (string) $danceStyle->id,
            ],

            'success_url' =>
                route(
                    'student.packages.success',
                    $studentPackage
                )
                . '?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' =>
                route(
                    'student.bookings'
                ),
        ]);


        /*
        |--------------------------------------------------------------------------
        | SAVE STRIPE SESSION
        |--------------------------------------------------------------------------
        */

        $studentPackage->update([
            'payment_provider' =>
                'stripe',

            'stripe_checkout_session_id' =>
                $session->id,
        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO STRIPE
        |--------------------------------------------------------------------------
        */

        return redirect()->away(
            $session->url
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PACKAGE PAYMENT SUCCESS
    |--------------------------------------------------------------------------
    */

    public function success(
        StudentPackage $studentPackage
    )
    {
        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        $student = Student::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $studentPackage->student_id
                ===
            (int) $student->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | ALREADY ACTIVE / COMPLETED
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $studentPackage->status,
                [
                    'active',
                    'completed',
                ],
                true
            )
        ) {
            return redirect()
                ->route(
                    'student.bookings'
                )
                ->with(
                    'success',
                    app()->getLocale() === 'fr'
                        ? 'Ce forfait a déjà été activé.'
                        : 'This package has already been activated.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | STRIPE SESSION ID
        |--------------------------------------------------------------------------
        */

        $sessionId = request(
            'session_id'
        );

        if (!$sessionId) {
            return redirect()
                ->route(
                    'student.bookings'
                )
                ->with(
                    'error',
                    app()->getLocale() === 'fr'
                        ? 'Session de paiement invalide.'
                        : 'Invalid payment session.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFY EXPECTED STRIPE SESSION
        |--------------------------------------------------------------------------
        */

        if (
            $studentPackage
                ->stripe_checkout_session_id
            &&
            $studentPackage
                ->stripe_checkout_session_id
                !==
            $sessionId
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | STRIPE
        |--------------------------------------------------------------------------
        */

        Stripe::setApiKey(
            env('STRIPE_SECRET')
        );

        $session = Session::retrieve(
            $sessionId
        );


        /*
        |--------------------------------------------------------------------------
        | VERIFY PAYMENT
        |--------------------------------------------------------------------------
        */

        if (
            $session->payment_status
            !==
            'paid'
        ) {
            return redirect()
                ->route(
                    'student.bookings'
                )
                ->with(
                    'error',
                    app()->getLocale() === 'fr'
                        ? 'Le paiement du forfait n’a pas été complété.'
                        : 'The package payment was not completed.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFY PACKAGE ID
        |--------------------------------------------------------------------------
        */

        $stripePackageId = (int) (
            $session
                ->metadata
                ->student_package_id
            ?? 0
        );

        if (
            $stripePackageId
            !==
            (int) $studentPackage->id
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFY STUDENT ID
        |--------------------------------------------------------------------------
        */

        $stripeStudentId = (int) (
            $session
                ->metadata
                ->student_id
            ?? 0
        );

        if (
            $stripeStudentId
            !==
            (int) $student->id
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | STRIPE TRANSACTION ID
        |--------------------------------------------------------------------------
        */

        $transactionId =
            $session->payment_intent
            ?: $session->id;


        /*
        |--------------------------------------------------------------------------
        | ACTIVATE PACKAGE
        |--------------------------------------------------------------------------
        */

        $studentPackage->update([
            'status' =>
                'active',

            'payment_provider' =>
                'stripe',

            'transaction_id' =>
                $transactionId,

            'stripe_checkout_session_id' =>
                $session->id,

            'purchased_at' =>
                now(),

            'sessions_used' =>
                0,

            'sessions_remaining' =>
                $studentPackage->package_size,
        ]);


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'student.bookings'
            )
            ->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Votre forfait a été acheté et activé avec succès.'
                    : 'Your package was purchased and activated successfully.'
            );
    }
}