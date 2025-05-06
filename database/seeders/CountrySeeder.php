<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // store returned data into array of records
        $records = importCsv(public_path("/seeders/countries.csv"));

        // add each record to the posts table in DB       
        foreach ($records as $key => $record) {
            Country::create([
                'id' => $record['id'],
                'name' => $record['name'],
                'code' => $record['code'],
                'status' => $record['status'],
            ]);
        }
    }
}
