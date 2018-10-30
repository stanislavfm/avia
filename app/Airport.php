<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string|null $name
 * @property string $location
 * @property int $timezoneOffset
 * @property-read \App\Flight[] $flightDepartures
 * @property-read \App\Flight[] $flightArrivals
 * @mixin \Eloquent
 */
class Airport extends Model
{
    const CODE_LENGTH = 3;
    const LOCATION_LENGTH = 25;

    public $timestamps = false;

    public function flightDepartures()
    {
        return $this->hasMany('App\Flight', 'departureAirportId');
    }

    public function flightArrivals()
    {
        return $this->hasMany('App\Flight', 'arrivalAirportId');
    }
}
