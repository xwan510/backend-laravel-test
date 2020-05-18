<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with CSV files.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PropertySeeder::class,
            AnalyticTypeSeeder::class,
            PropertyAnalyticSeeder::class,
        ]);
    }
}
