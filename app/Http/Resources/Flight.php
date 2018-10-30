<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'departureTime'     => $this->departureTime,
            'arrivalTime'       => $this->arrivalTime,
        ];
    }
}
