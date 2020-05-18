<?php

use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Seed properties table from csv.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path().'/database/seeds/csvs/properties.csv';
        $csv = fopen($path, 'r');
        // Skip header row.
        $header = fgetcsv($csv, 0, ',');
        while (($row = fgetcsv($csv, 0, ',')) != false) {
            DB::table('properties')->insert(
                array(
                    'suburb' => $row[1],
                    'state' => $row[2],
                    'country' => $row[3],
                    'guid' => (string) Str::uuid(),
                    'created_at' =>  Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' =>  Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                )
            );
        }
    }
}
