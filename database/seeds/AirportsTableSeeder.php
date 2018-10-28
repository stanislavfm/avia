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

    /**
     * @see CreateAirportsTable
     * @return array
     */
    private function getData()
    {
        return [
            ['code' => 'SIN', 'name' => 'Singapore Changi Airport', 'location' => '1.359211,103.989306', 'timezone_offset' => 8],
            ['code' => 'ICN', 'name' => 'Incheon International Airport', 'location' => '37.463333,126.44', 'timezone_offset' => 9],
            ['code' => 'MUC', 'name' => 'Munich Airport', 'location' => '48.353889,11.786111', 'timezone_offset' => 1],
            ['code' => 'HND', 'name' => 'Haneda Airport', 'location' => '35.553333,139.781111', 'timezone_offset' => 9],
            ['code' => 'AMS', 'name' => 'Amsterdam Airport Schiphol', 'location' => '52.308056,4.764167', 'timezone_offset' => 1],
            ['code' => 'YVR', 'name' => 'Vancouver International Airport', 'location' => '49.194722,-123.183889', 'timezone_offset' => -8],
            ['code' => 'LHR', 'name' => 'Heathrow Airport', 'location' => '51.4775,-0.461389', 'timezone_offset' => 0],
            ['code' => 'ZRH', 'name' => 'Zurich Airport', 'location' => '47.464722,8.549167', 'timezone_offset' => 1],
            ['code' => 'FRA', 'name' => 'Frankfurt Airport', 'location' => '50.033333,8.570556', 'timezone_offset' => 1],
            ['code' => 'HEL', 'name' => 'Helsinki Airport', 'location' => '60.317222,24.963333', 'timezone_offset' => 2],
            ['code' => 'CPH', 'name' => 'Copenhagen Airport', 'location' => '55.618056,12.656111', 'timezone_offset' => 1],
            ['code' => 'CGN', 'name' => 'Cologne Bonn Airport', 'location' => '50.865833,7.142778', 'timezone_offset' => 1],
            ['code' => 'AKL', 'name' => 'Auckland Airport', 'location' => '-37.008056,174.791667', 'timezone_offset' => 12],
            ['code' => 'CPT', 'name' => 'Cape Town International Airport', 'location' => '-33.969444,18.597222', 'timezone_offset' => 2],
            ['code' => 'SYD', 'name' => 'Sydney Airport', 'location' => '-33.946111,151.177222', 'timezone_offset' => 10],
            ['code' => 'KUL', 'name' => 'Kuala Lumpur International Airport', 'location' => '2.743333,101.698056', 'timezone_offset' => 8],
            ['code' => 'MEL', 'name' => 'Melbourne Airport', 'location' => '-37.673333,144.843333', 'timezone_offset' => 10],
            ['code' => 'DXB', 'name' => 'Dubai International Airport', 'location' => '25.252778,55.364444', 'timezone_offset' => 4],
            ['code' => 'DEN', 'name' => 'Denver International Airport', 'location' => '39.861667,-104.673056', 'timezone_offset' => -7],
            ['code' => 'VIE', 'name' => 'Vienna International Airport', 'location' => '48.110833,16.570833', 'timezone_offset' => 1],
            ['code' => 'SFO', 'name' => 'San Francisco International Airport', 'location' => '37.618889,-122.375', 'timezone_offset' => -8],
            ['code' => 'AUH', 'name' => 'Abu Dhabi International Airport', 'location' => '24.433056,54.651111', 'timezone_offset' => 4],
            ['code' => 'HAM', 'name' => 'Hamburg Airport', 'location' => '53.630278,9.991111', 'timezone_offset' => 1],
            ['code' => 'LGW', 'name' => 'Gatwick Airport', 'location' => '51.148056,-0.190278', 'timezone_offset' => 0],
            ['code' => 'DME', 'name' => 'Moscow Domodedovo Airport', 'location' => '55.408611,37.906111', 'timezone_offset' => 3],
            ['code' => 'LIM', 'name' => 'Jorge Chavez International Airport', 'location' => '-12.021944,-77.114444', 'timezone_offset' => -5],
            ['code' => 'OSL', 'name' => 'Oslo Airport', 'location' => '60.202778,11.083889', 'timezone_offset' => 1],
            ['code' => 'LIS', 'name' => 'Lisbon Airport', 'location' => '38.774167,-9.134167', 'timezone_offset' => 0],
            ['code' => 'JFK', 'name' => 'John F. Kennedy International Airport', 'location' => '40.639722,-73.778889', 'timezone_offset' => -5],
            ['code' => 'ATH', 'name' => 'Athens International Airport', 'location' => '37.936389,23.947222', 'timezone_offset' => 2],
        ];
    }
}
