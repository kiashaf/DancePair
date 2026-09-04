<?php

namespace App\Http\Controllers;

use App\Models\CommissionHistory;
use App\Models\DanceStyle;
use App\Models\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminSettingsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SETTINGS PAGE
    |--------------------------------------------------------------------------
    */

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


        /*
        |--------------------------------------------------------------------------
        | DANCE TYPES
        |--------------------------------------------------------------------------
        */

        $danceStyles = DanceStyle::query()
            ->orderBy('name')
            ->get();


        return view(
            'admin.settings',
            compact(
                'admin',
                'platformCommissionPercent',
                'commissionHistory',
                'danceStyles'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE GENERAL SETTINGS
    |--------------------------------------------------------------------------
    */

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
        | UPDATE ADMIN
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
        | OLD COMMISSION
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
        | COMMISSION HISTORY
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


        Setting::setValue(
            'platform_commission_percent',
            $newCommissionPercent
        );


        return back()->with(
            'success',
            'Admin settings and DancePair commission updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADD DANCE TYPE
    |--------------------------------------------------------------------------
    */

    public function storeDanceStyle(Request $request)
    {
        $validated = $request->validate([

            'dance_style_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    'dance_styles',
                    'name'
                ),
            ],

            'dance_style_description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'dance_style_active' => [
                'required',
                'boolean',
            ],
        ]);


        $name = trim(
            $validated['dance_style_name']
        );


        /*
        |--------------------------------------------------------------------------
        | CREATE UNIQUE SLUG
        |--------------------------------------------------------------------------
        */

        $slug = $this->makeUniqueDanceStyleSlug(
            $name
        );


        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        $danceStyle = new DanceStyle();

        $danceStyle->name =
            $name;

        $danceStyle->slug =
            $slug;

        $danceStyle->description =
            $validated[
                'dance_style_description'
            ] ?? null;

        $danceStyle->active =
            (bool) $validated[
                'dance_style_active'
            ];

        $danceStyle->save();


        return back()->with(
            'success',
            'Dance type "' .
            $danceStyle->name .
            '" added successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE DANCE TYPE
    |--------------------------------------------------------------------------
    */

    public function updateDanceStyle(
        Request $request,
        DanceStyle $danceStyle
    ) {
        $validated = $request->validate([

            'dance_style_name' => [
                'required',
                'string',
                'max:255',

                Rule::unique(
                    'dance_styles',
                    'name'
                )->ignore(
                    $danceStyle->id
                ),
            ],

            'dance_style_description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'dance_style_active' => [
                'required',
                'boolean',
            ],
        ]);


        $name = trim(
            $validated['dance_style_name']
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE SLUG IF NAME CHANGED
        |--------------------------------------------------------------------------
        */

        if ($name !== $danceStyle->name) {

            $danceStyle->slug =
                $this->makeUniqueDanceStyleSlug(
                    $name,
                    $danceStyle->id
                );
        }


        $danceStyle->name =
            $name;

        $danceStyle->description =
            $validated[
                'dance_style_description'
            ] ?? null;

        $danceStyle->active =
            (bool) $validated[
                'dance_style_active'
            ];

        $danceStyle->save();


        return back()->with(
            'success',
            'Dance type updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVATE / DEACTIVATE DANCE TYPE
    |--------------------------------------------------------------------------
    */

    public function toggleDanceStyle(
        DanceStyle $danceStyle
    ) {
        $danceStyle->active =
            !$danceStyle->active;

        $danceStyle->save();


        return back()->with(
            'success',
            $danceStyle->active
                ? 'Dance type activated successfully.'
                : 'Dance type deactivated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UNIQUE DANCE TYPE SLUG
    |--------------------------------------------------------------------------
    */

    private function makeUniqueDanceStyleSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);


        if ($baseSlug === '') {

            $baseSlug = 'dance-style';
        }


        $slug = $baseSlug;

        $counter = 2;


        while (
            DanceStyle::query()

                ->when(
                    $ignoreId,
                    function ($query) use ($ignoreId) {

                        $query->where(
                            'id',
                            '!=',
                            $ignoreId
                        );
                    }
                )

                ->where(
                    'slug',
                    $slug
                )

                ->exists()
        ) {

            $slug =
                $baseSlug .
                '-' .
                $counter;

            $counter++;
        }


        return $slug;
    }
}