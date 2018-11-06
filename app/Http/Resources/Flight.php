<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Flight
 */
class Flight extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'number'            => $this->number,
            'transporter'       => new Transporter($this->transporter),
            'departureAirport'  => new Airport($this->departureAirport),
            'arrivalAirport'    => new Airport($this->arrivalAirport),
            'departureTime'     => $this->departureTime->format('Y-m-d H:i:s'),
            'arrivalTime'       => $this->arrivalTime->format('Y-m-d H:i:s'),
        ];
    }
}
