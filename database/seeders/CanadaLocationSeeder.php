<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Province;

class CanadaLocationSeeder extends Seeder
{
    public function run(): void
    {
        $canada = Country::updateOrCreate(
            ['code' => 'CA'],
            ['name' => 'Canada']
        );

        $provinces = [
            ['name' => 'Alberta', 'code' => 'AB'],
            ['name' => 'British Columbia', 'code' => 'BC'],
            ['name' => 'Manitoba', 'code' => 'MB'],
            ['name' => 'New Brunswick', 'code' => 'NB'],
            ['name' => 'Newfoundland and Labrador', 'code' => 'NL'],
            ['name' => 'Nova Scotia', 'code' => 'NS'],
            ['name' => 'Ontario', 'code' => 'ON'],
            ['name' => 'Prince Edward Island', 'code' => 'PE'],
            ['name' => 'Quebec', 'code' => 'QC'],
            ['name' => 'Saskatchewan', 'code' => 'SK'],
            ['name' => 'Northwest Territories', 'code' => 'NT'],
            ['name' => 'Nunavut', 'code' => 'NU'],
            ['name' => 'Yukon', 'code' => 'YT'],
        ];

        foreach ($provinces as $province) {
            Province::updateOrCreate(
                [
                    'country_id' => $canada->id,
                    'code' => $province['code'],
                ],
                [
                    'name' => $province['name'],
                ]
            );
        }
    }
}