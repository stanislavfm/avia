<?php

use Faker\Generator as Faker;

$factory->define(App\Flight::class, function (Faker $faker) {
    return [
        'number' => $faker->unique()->numberBetween(1, 9999),
        'transporter' => function () {
            return App\Transporter::all('id')->random()->id;
        },
        'departureAirport' => function () {
            return App\Airport::all('id')->random()->id;
        },
        'arrivalAirport' => function (array $flight) {
            return  App\Airport::all('id')->reject(function (App\Airport $airport) use ($flight) {
                return $airport->id === $flight['departureAirport'];
            })->random()->id;
        },
        'departureTime' => function () use ($faker) {
            $departureTime = $faker->dateTimeInInterval('+1 day', '+1 year', 'UTC');
            $departureTime->setTime($departureTime->format('H'), $departureTime->format('i'), 0);
            return $departureTime;
        },
        'arrivalTime' => function (array $flight) {
            $departureLocation = App\Airport::find($flight['departureAirport'])->location;
            $arrivalLocation = App\Airport::find($flight['arrivalAirport'])->location;

            list ($latitude1, $longitude1) = explode(',', $departureLocation);
            list ($latitude2, $longitude2) = explode(',', $arrivalLocation);

            $theta = $longitude1 - $longitude2;
            $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
            $miles = acos($miles);
            $miles = rad2deg($miles);
            $miles = $miles * 60 * 1.1515;

            $distance = $miles * 1.609344; // km
            $flySpeed = 600; // km/h
            $flightDuration = round($distance / $flySpeed, 1) * 60; // min

            $arrivalTime = clone $flight['departureTime'];
            return $arrivalTime->modify('+' . $flightDuration . ' min');
        },
    ];
});
