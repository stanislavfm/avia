<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $data) {
            DB::table('airports')->insert($data);
        }
    }

    private function getData()
    {
        return [
            ['code' => 'SIN', 'name' => 'Singapore Changi Airport'],
            ['code' => 'ICN', 'name' => 'Incheon International Airport'],
            ['code' => 'MUC', 'name' => 'Munich Airport'],
            ['code' => 'HND', 'name' => 'Haneda Airport'],
            ['code' => 'AMS', 'name' => 'Amsterdam Airport Schiphol'],
            ['code' => 'YVR', 'name' => 'Vancouver International Airport'],
            ['code' => 'LHR', 'name' => 'Heathrow Airport'],
            ['code' => 'ZRH', 'name' => 'Zurich Airport'],
            ['code' => 'FRA', 'name' => 'Frankfurt Airport'],
            ['code' => 'HEL', 'name' => 'Helsinki Airport'],
            ['code' => 'CPH', 'name' => 'Copenhagen Airport'],
            ['code' => 'CGN', 'name' => 'Cologne Bonn Airport'],
            ['code' => 'AKL', 'name' => 'Auckland Airport'],
            ['code' => 'CPT', 'name' => 'Cape Town International Airport'],
            ['code' => 'SYD', 'name' => 'Sydney Airport'],
            ['code' => 'KUL', 'name' => 'Kuala Lumpur International Airport'],
            ['code' => 'MEL', 'name' => 'Melbourne Airport'],
            ['code' => 'DXB', 'name' => 'Dubai International Airport'],
            ['code' => 'DEN', 'name' => 'Denver International Airport'],
            ['code' => 'VIE', 'name' => 'Vienna International Airport'],
            ['code' => 'SFO', 'name' => 'San Francisco International Airport'],
            ['code' => 'AUH', 'name' => 'Abu Dhabi International Airport'],
            ['code' => 'HAM', 'name' => 'Hamburg Airport'],
            ['code' => 'LGW', 'name' => 'Gatwick Airport'],
            ['code' => 'DME', 'name' => 'Moscow Domodedovo Airport'],
            ['code' => 'LIM', 'name' => 'Jorge Chavez International Airport'],
            ['code' => 'OSL', 'name' => 'Oslo Airport'],
            ['code' => 'LIS', 'name' => 'Lisbon Airport'],
            ['code' => 'JFK', 'name' => 'John F. Kennedy International Airport'],
            ['code' => 'ATH', 'name' => 'Athens International Airport'],
        ];
    }
}
