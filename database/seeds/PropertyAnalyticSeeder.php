<?php

use Illuminate\Database\Seeder;

class PropertyAnalyticSeeder extends Seeder
{
    /**
     * Seed property_analytics table from csv.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path().'/database/seeds/csvs/property_analytics.csv';
        $csv = fopen($path, 'r');
        // Skip header row.
        $header = fgetcsv($csv, 0, ',');
        while (($row = fgetcsv($csv, 0, ',')) != false) {
            DB::table('property_analytics')->insert(
                array(
                    'property_id' => (int) $row[0],
                    'analytic_type_id' => (int) $row[1],
                    'value' => $row[2],
                    'created_at' =>  Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' =>  Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                )
            );
        }
    }
}
