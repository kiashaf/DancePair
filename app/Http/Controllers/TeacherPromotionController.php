<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Setting;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeacherPromotionController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $promotions = Promotion::where(
            'teacher_id',
            $teacher->id
        )
            ->latest()
            ->get();

        return view(
            'teacher.promotions.index',
            compact(
                'teacher',
                'promotions'
            )
        );
    }

    public function create()
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $maximumPackageDiscountPercent = (int) Setting::getValue(
            'maximum_package_discount_percent',
            50
        );

        $maximumDiscountCodePercent = (int) Setting::getValue(
            'maximum_discount_code_percent',
            50
        );

        return view(
            'teacher.promotions.create',
            compact(
                'teacher',
                'maximumPackageDiscountPercent',
                'maximumDiscountCodePercent'
            )
        );
    }

    public function store(Request $request)
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $maximumPackageDiscountPercent = (int) Setting::getValue(
            'maximum_package_discount_percent',
            50
        );

        $maximumDiscountCodePercent = (int) Setting::getValue(
            'maximum_discount_code_percent',
            50
        );

        $maximumDiscountPercent =
            $request->input('type') === 'package_discount'
                ? $maximumPackageDiscountPercent
                : $maximumDiscountCodePercent;

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                Rule::in([
                    'free_session',
                    'package_discount',
                    'discount_code',
                ]),
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
                'alpha_dash',
                'unique:promotions,code',
            ],

            'package_size' => [
                'nullable',
                'integer',
                Rule::in([
                    5,
                    10,
                    20,
                ]),
            ],

            'discount_percent' => [
                'nullable',
                'integer',
                'min:1',
                'max:' . $maximumDiscountPercent,
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'starts_at' => [
                'nullable',
                'date',
            ],

            'ends_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        if ($validated['type'] === 'free_session') {
            $validated['package_size'] = null;
            $validated['discount_percent'] = null;
            $validated['code'] = null;
            $validated['usage_limit'] = null;
            $validated['free_sessions_count'] = 1;
        }

        if ($validated['type'] === 'package_discount') {
            $validated['code'] = null;
            $validated['usage_limit'] = null;
            $validated['free_sessions_count'] = 0;
        }

        if ($validated['type'] === 'discount_code') {
            $validated['package_size'] = null;
            $validated['free_sessions_count'] = 0;

            $validated['code'] =
                strtoupper(
                    $validated['code'] ?? ''
                );

            $validated['used_count'] = 0;
        }

        $validated['teacher_id'] =
            $teacher->id;

        $validated['is_active'] =
            $request->boolean('is_active');

        Promotion::create(
            $validated
        );

        return redirect()
            ->route('teacher.promotions.index')
            ->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Promotion créée avec succès.'
                    : 'Promotion created successfully.'
            );
    }

    public function edit(Promotion $promotion)
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        abort_if(
            $promotion->teacher_id !== $teacher->id,
            403
        );

        $maximumPackageDiscountPercent = (int) Setting::getValue(
            'maximum_package_discount_percent',
            50
        );

        $maximumDiscountCodePercent = (int) Setting::getValue(
            'maximum_discount_code_percent',
            50
        );

        return view(
            'teacher.promotions.edit',
            compact(
                'teacher',
                'promotion',
                'maximumPackageDiscountPercent',
                'maximumDiscountCodePercent'
            )
        );
    }

    public function update(
        Request $request,
        Promotion $promotion
    ) {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        abort_if(
            $promotion->teacher_id !== $teacher->id,
            403
        );

        $maximumPackageDiscountPercent = (int) Setting::getValue(
            'maximum_package_discount_percent',
            50
        );

        $maximumDiscountCodePercent = (int) Setting::getValue(
            'maximum_discount_code_percent',
            50
        );

        $maximumDiscountPercent =
            $request->input('type') === 'package_discount'
                ? $maximumPackageDiscountPercent
                : $maximumDiscountCodePercent;

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                Rule::in([
                    'free_session',
                    'package_discount',
                    'discount_code',
                ]),
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique(
                    'promotions',
                    'code'
                )->ignore(
                    $promotion->id
                ),
            ],

            'package_size' => [
                'nullable',
                'integer',
                Rule::in([
                    5,
                    10,
                    20,
                ]),
            ],

            'discount_percent' => [
                'nullable',
                'integer',
                'min:1',
                'max:' . $maximumDiscountPercent,
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'starts_at' => [
                'nullable',
                'date',
            ],

            'ends_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        if ($validated['type'] === 'free_session') {
            $validated['package_size'] = null;
            $validated['discount_percent'] = null;
            $validated['code'] = null;
            $validated['usage_limit'] = null;
            $validated['free_sessions_count'] = 1;
        }

        if ($validated['type'] === 'package_discount') {
            $validated['code'] = null;
            $validated['usage_limit'] = null;
            $validated['free_sessions_count'] = 0;
        }

        if ($validated['type'] === 'discount_code') {
            $validated['package_size'] = null;
            $validated['free_sessions_count'] = 0;

            $validated['code'] =
                strtoupper(
                    $validated['code'] ?? ''
                );
        }

        $validated['is_active'] =
            $request->boolean('is_active');

        $promotion->update(
            $validated
        );

        return redirect()
            ->route('teacher.promotions.index')
            ->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Promotion mise à jour avec succès.'
                    : 'Promotion updated successfully.'
            );
    }

    public function destroy(
        Promotion $promotion
    ) {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        abort_if(
            $promotion->teacher_id !== $teacher->id,
            403
        );

        $promotion->delete();

        return redirect()
            ->route('teacher.promotions.index')
            ->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Promotion supprimée.'
                    : 'Promotion deleted.'
            );
    }
}