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
use App\Http\Resources;
use App\Airport;
use App\Flight;
use App\Transporter;

class GetController extends Controller
{
    public function airports(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'min:1', 'max:' . Builder::$defaultStringLength],
            'code' => ['sometimes', 'required', 'string', 'size:' . Airport::CODE_LENGTH]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        if ($request->has(['name', 'code'])) {
            return [
                'status' => false,
                'message' => 'It needs to specify one query parameter.'
            ];
        }

        if ($request->has('name')) {
            $airports = Airport::where('name', 'like', '%' . $request->input('name') . '%')->get();
        } elseif ($request->has('code')) {
            $airports = Airport::where('code', $request->input('code'))->get();
        } else {
            $airports = Airport::all();
        }

        if ($airports->isEmpty()) {
            return [
                'status' => false,
                'message' => 'No airports found.'
            ];
        }

        return Resources\Airport::collection($airports);
    }

    public function transporters(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'min:1', 'max:' . Builder::$defaultStringLength],
            'code' => ['sometimes', 'required', 'string', 'size:' . Transporter::CODE_LENGTH]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        if ($request->has(['name', 'code'])) {
            return [
                'status' => false,
                'message' => 'It needs to specify one query parameter.'
            ];
        }

        if ($request->has('name')) {
            $transporters = Transporter::where('name', 'like', '%' . $request->input('name') . '%')->get();
        } elseif ($request->has('code')) {
            $transporters = Transporter::where('code', $request->input('code'))->get();
        } else {
            $transporters = Transporter::all();
        }

        if ($transporters->isEmpty()) {
            return [
                'status' => false,
                'message' => 'No transporters found.'
            ];
        }

        return Resources\Transporter::collection($transporters);
    }

    public function flights(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'number' => ['sometimes', 'required', 'string', 'max:' . Flight::NUMBER_LENGTH, 'regex:/^\d+$/'],
            'transporter' => ['sometimes', 'required', 'string', 'size:' . Transporter::CODE_LENGTH, 'exists:transporters,code'],
            'departureAirport'  => ['sometimes', 'required', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
            'arrivalAirport'    => ['sometimes', 'required', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
            'departureTime'     => ['sometimes', 'required', 'date'],
            'arrivalTime'       => ['sometimes', 'required', 'date'],
            'departureTimeFrom' => ['sometimes', 'required_with:departureTimeTo', 'date'],
            'departureTimeTo'   => ['sometimes', 'required_with:departureTimeFrom', 'date'],
            'arrivalTimeFrom'   => ['sometimes', 'required_with:arrivalTimeTo', 'date'],
            'arrivalTimeTo'     => ['sometimes', 'required_with:arrivalTimeFrom', 'date'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        if (
            $request->has('departureTime')
            && $request->hasAny(['departureTimeFrom', 'departureTimeTo'])
        ) {
            return [
                'status' => false,
                'message' => 'Invalid request parameters. Departure time should be specified without departure time from or departure time to fields.'
            ];
        }

        if (
            $request->has('arrivalTime')
            && $request->hasAny(['arrivalTimeFrom', 'arrivalTimeTo'])
        ) {
            return [
                'status' => false,
                'message' => 'Invalid request parameters. Arrival time should be specified without arrival time from or arrival time to fields.'
            ];
        }

        if (
            $request->hasAny(['departureTimeFrom', 'departureTimeTo'])
            && !$request->has(['departureTimeFrom', 'departureTimeTo'])
        ) {
            return [
                'status' => false,
                'message' => 'Invalid request parameters. It should be specified both departure time from and departure time to fields.'
            ];
        }

        if (
            $request->hasAny(['arrivalTimeFrom', 'arrivalTimeTo'])
            && !$request->has(['arrivalTimeFrom', 'arrivalTimeTo'])
        ) {
            return [
                'status' => false,
                'message' => 'Invalid request parameters. It should be specified both arrival time from and arrival time to fields.'
            ];
        }

        $flightsQuery = Flight::query();

        if ($request->has('number')) {
            $flightsQuery->where('number', $request->input('number'));
        }

        if ($request->has('transporter')) {
            $flightsQuery->whereHas('transporter', function ($query) use ($request) {
                $query->where('code', $request->input('transporter'));
            });
        }

        if ($request->has('departureAirport')) {
            $flightsQuery->whereHas('departureAirport', function ($query) use ($request) {
                $query->where('code', $request->input('departureAirport'));
            });
        }

        if ($request->has('arrivalAirport')) {
            $flightsQuery->whereHas('arrivalAirport', function ($query) use ($request) {
                $query->where('code', $request->input('arrivalAirport'));
            });
        }

        if ($request->has('departureTime')) {
            $flightsQuery->where('departureTime', $request->input('departureTime'));
        }

        if ($request->has('arrivalTime')) {
            $flightsQuery->where('arrivalTime', $request->input('arrivalTime'));
        }

        if ($request->has(['departureTimeFrom', 'departureTimeTo'])) {
            $flightsQuery->whereBetween('departureTime', [$request->input('departureTimeFrom'), $request->input('departureTimeTo')]);
        }

        if ($request->has(['arrivalTimeFrom', 'departureTimeTo'])) {
            $flightsQuery->whereBetween('arrivalTime', [$request->input('arrivalTimeFrom'), $request->input('arrivalTimeTo')]);
        }

        $flights = $flightsQuery->get();

        if ($flights->isEmpty()) {
            return [
                'status' => false,
                'message' => 'No flights found.'
            ];
        }

        return Resources\Flight::collection($flights);
    }
}