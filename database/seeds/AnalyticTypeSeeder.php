<?php

use Illuminate\Database\Seeder;

class AnalyticTypeSeeder extends Seeder
{
    /**
     * Seed analytic_types table from csv.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path().'/database/seeds/csvs/analytic_types.csv';
        $csv = fopen($path, 'r');
        // Skip header row.
        $header = fgetcsv($csv, 0, ',');
        while (($row = fgetcsv($csv, 0, ',')) != false) {
            DB::table('analytic_types')->insert(
                array(
                    'name' => $row[1],
                    'units' => $row[2],
                    'is_numeric' => ($row[3] === 'TRUE' ? true : false),
                    'num_decimal_places' => (int) $row[4],
                    'created_at' =>  Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' =>  Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                )
            );
        }
    }
}
