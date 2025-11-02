<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostGISSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $result = DB::select('SELECT PostGIS_Full_Version();');

        if (!(isset($result[0]) && property_exists($result[0], 'postgis_full_version'))) {
            DB::unprepared('CREATE EXTENSION postgis;');
        }
    }
}
