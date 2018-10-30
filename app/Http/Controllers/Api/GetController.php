<?php
/**
 * Url Rewrite Import for Magento 1
 * @author Stanislav Chertilin <staschertilin@gmail.com>
 * @copyright 2018 https://github.com/stanislavfm/url-rewrite-import-m1
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\Schema\Builder;
use App\Http\Controllers\Controller;
use App\Rules;
use App\Airport;
use App\Http\Resources\Airport as AirportResource;
use App\Http\Resources\Transporter as TransporterResource;
use App\Http\Resources\Flight as FlightResource;
use App\Flight;
use App\Transporter;

class GetController extends Controller
{
    public function airport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => ['required', 'string', 'size:' . Airport::CODE_LENGTH]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $airport = Airport::where('code', $request->input('code'))->first();

        if (is_null($airport)) {
            return [
                'status' => false,
                'message' => 'No airport found.'
            ];
        }

        return new AirportResource($airport);
    }

    public function airports(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['string', 'min:1', 'max:' . Builder::$defaultStringLength]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        if ($request->has('name')) {
            $airports = Airport::where('name', 'like', '%' . $request->input('name') . '%');
        } else {
            $airports = Airport::all();
        }

        return AirportResource::collection($airports);
    }

    public function transporter(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => ['required', 'string', 'size:' . Transporter::CODE_LENGTH]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $transporter = Transporter::where('code', $request->input('code'))->first();

        if (is_null($transporter)) {
            return [
                'status' => false,
                'message' => 'No transporter found.'
            ];
        }

        return new TransporterResource($transporter);
    }

    public function transporters(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['string', 'min:1', 'max:' . Builder::$defaultStringLength]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        if ($request->has('name')) {
            $airports = Transporter::where('name', 'like', '%' . $request->input('name') . '%');
        } else {
            $airports = Transporter::all();
        }

        return TransporterResource::collection($airports);
    }

    public function flight(Request $request)
    {
        $flightNumberValidator = new Rules\FlightNumber();

        $validator = \Validator::make($request->all(), [
            'number' => ['required', $flightNumberValidator]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $flightNumber = $flightNumberValidator->getFlightNumber();
        $transporterCode = $flightNumberValidator->getTransporterCode();

        $flight = Flight::where('number', $flightNumber)->first();

        if (is_null($flight)) {
            return [
                'status' => false,
                'message' => 'No flight found.',
            ];
        }

        if ($flight->transporter->code !== $transporterCode) {
            return [
                'status' => false,
                'message' => 'No flight with given transporter found.'
            ];
        }

        return new FlightResource($flight);
    }

    public function flightsSearch(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'departureAirport' => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
            'arrivalAirport'   => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
            'departureDate'    => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $departureDateFrom = new \DateTime($request->input('departureDate'), new \DateTimeZone('UTC'));

        $departureDateTo = clone $departureDateFrom;
        $departureDateTo->setTime(23, 59, 59);

        $flights = Flight
            ::whereHas('departureAirport', function ($query) use ($request) {
                $query->where('code', $request->input('departureAirport'));
            })
            ->whereHas('arrivalAirport', function ($query) use ($request) {
                $query->where('code', $request->input('arrivalAirport'));
            })
            ->whereBetween('departureTime', [$departureDateFrom, $departureDateTo]);

        if (!$flights->count()) {
            return [
                'status' => false,
                'message' => 'No flights found.'
            ];
        }

        return FlightResource::collection($flights->get());
    }
}