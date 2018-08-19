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
            $departureTime = $faker->dateTimeInInterval('+1 day', '+1 year');
            $departureTime->setTime($departureTime->format('H'), $departureTime->format('i'), 0);
            return $departureTime;
        },
        'arrivalTime' => function (array $flight) use ($faker) {
            $arrivalTime = clone $flight['departureTime'];
            $minFlightDuration = 30; //30 minutes
            $maxFlightDuration = 1200; //20 hours
            $flightDuration = $faker->numberBetween($minFlightDuration, $maxFlightDuration);
            return $arrivalTime->modify('+' . $flightDuration . ' min');
        },
    ];
});
