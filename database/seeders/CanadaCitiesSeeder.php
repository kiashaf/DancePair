<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class CanadaCitiesSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/CA.txt');

        if (!file_exists($path)) {
            $this->command->error('CA.txt not found at: ' . $path);
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | GeoNames Canada admin1 code => Province code
        |--------------------------------------------------------------------------
        */

        $provinceMap = [
            '01' => 'AB',
            '02' => 'BC',
            '03' => 'MB',
            '04' => 'NB',
            '05' => 'NL',
            '07' => 'NS',
            '08' => 'ON',
            '09' => 'PE',
            '10' => 'QC',
            '11' => 'SK',
            '12' => 'YT',
            '13' => 'NT',
            '14' => 'NU',
        ];

        /*
        |--------------------------------------------------------------------------
        | Load provinces once
        |--------------------------------------------------------------------------
        */

        $provinces = Province::whereIn('code', array_values($provinceMap))
            ->get()
            ->keyBy('code');

        $handle = fopen($path, 'r');

        $inserted = 0;
        $skipped = 0;

        while (($line = fgets($handle)) !== false) {

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | GeoNames is TAB separated
            |--------------------------------------------------------------------------
            */

            $columns = explode("\t", $line);

            if (count($columns) < 11) {
                $skipped++;
                continue;
            }

            $cityName = trim($columns[1]);

            $featureClass = trim($columns[6]);

            $countryCode = trim($columns[8]);

            $admin1Code = trim($columns[10]);

            /*
            |--------------------------------------------------------------------------
            | We only want Canadian populated places
            |
            | P = city / town / village / populated place
            |--------------------------------------------------------------------------
            */

            if ($countryCode !== 'CA') {
                continue;
            }

            if ($featureClass !== 'P') {
                continue;
            }

            if (!isset($provinceMap[$admin1Code])) {
                $skipped++;
                continue;
            }

            $provinceCode = $provinceMap[$admin1Code];

            if (!isset($provinces[$provinceCode])) {
                $skipped++;
                continue;
            }

            if ($cityName === '') {
                continue;
            }

            $city = City::firstOrCreate([
                'province_id' => $provinces[$provinceCode]->id,
                'name' => $cityName,
            ]);

            if ($city->wasRecentlyCreated) {
                $inserted++;
            }
        }

        fclose($handle);

        $this->command->info("Cities inserted: {$inserted}");
        $this->command->info("Rows skipped: {$skipped}");
    }
}