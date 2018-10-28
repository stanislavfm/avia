<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $data) {
            DB::table('transporters')->insert($data);
        }
    }

    /**
     * @see CreateTransportersTable
     * @return array
     */
    private function getData()
    {
        return [
            ['code' => 'QR', 'name' => 'Qatar Airways'],
            ['code' => 'SQ', 'name' => 'Singapore Airlines'],
            ['code' => 'EK', 'name' => 'Emirates'],
            ['code' => 'CX', 'name' => 'Cathay Pacific'],
            ['code' => 'BR', 'name' => 'EVA Air'],
            ['code' => 'LH', 'name' => 'Lufthansa'],
            ['code' => 'EY', 'name' => 'Etihad Airways'],
            ['code' => 'HU', 'name' => 'Hainan Airlines'],
            ['code' => 'GA', 'name' => 'Garuda Indonesia'],
            ['code' => 'TG', 'name' => 'Thai Airways'],
            ['code' => 'TK', 'name' => 'Turkish Airlines'],
            ['code' => 'DJ', 'name' => 'Virgin Australia'],
            ['code' => 'QF', 'name' => 'Qantas Airlines'],
            ['code' => 'JL', 'name' => 'Japan Airlines'],
            ['code' => 'AF', 'name' => 'Air France'],
            ['code' => 'NZ', 'name' => 'Air New Zealand'],
            ['code' => 'OZ', 'name' => 'Asiana Airlines'],
            ['code' => 'AK', 'name' => 'AirAsia'],
            ['code' => 'DY', 'name' => 'Norwegian Air Shuttle'],
            ['code' => 'B6', 'name' => 'JetBlue Airways'],
        ];
    }
}
