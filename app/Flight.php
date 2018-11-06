<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $number
 * @property int $transporterId
 * @property int $departureAirportId
 * @property int $arrivalAirportId
 * @property \Illuminate\Support\Carbon $departureTime
 * @property \Illuminate\Support\Carbon $arrivalTime
 * @property-read \App\Airport $arrivalAirport
 * @property-read \App\Airport $departureAirport
 * @property-read \App\Transporter $transporter
 * @mixin \Eloquent
 */
class Flight extends Model
{
    const NUMBER_LENGTH = 4;

    public $timestamps = false;

    protected $dates = ['departureTime', 'arrivalTime'];
    protected $fillable = ['number', 'departureTime', 'arrivalTime'];

    public function transporter()
    {
        return $this->belongsTo('App\Transporter', 'transporterId');
    }

    public function departureAirport()
    {
        return $this->belongsTo('App\Airport', 'departureAirportId');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo('App\Airport', 'arrivalAirportId');
    }
}
