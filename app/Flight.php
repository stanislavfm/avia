<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $number
 * @property int $transporterId
 * @property int $departureAirportId
 * @property int $arrivalAirportId
 * @property string $departureTime
 * @property string $arrivalTime
 * @property-read \App\Airport $arrivalAirport
 * @property-read \App\Airport $departureAirport
 * @property-read \App\Transporter $transporter
 * @mixin \Eloquent
 */
class Flight extends Model
{
    public $timestamps = false;

    public function transporter()
    {
        return $this->hasOne('App\Transporter', 'id', 'transporterId');
    }

    public function departureAirport()
    {
        return $this->hasOne('App\Airport', 'id', 'departureAirportId');
    }

    public function arrivalAirport()
    {
        return $this->hasOne('App\Airport', 'id', 'arrivalAirportId');
    }
}
