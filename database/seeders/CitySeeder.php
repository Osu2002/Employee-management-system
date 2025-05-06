<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // store returned data into array of records
        $records = importCsv(public_path("/seeders/cities.csv"));

        // add each record to the posts table in DB       
        foreach ($records as $key => $record) {
            City::create([
                'id' => $record['id'],
                'district_id' => $record['district_id'],
                'name' => $record['name'],
                'postcode' => $record['postcode'],
                'latitude' => $record['latitude'],
                'longitude' => $record['longitude'],
                'status' => $record['status'],
            ]);
        }
    }
}
