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
use App\Transporter;
use App\Flight;

class PutController extends Controller
{
    public function airport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
            'name' => ['sometimes', 'required_without_all:location,timezoneOffset', 'string', 'max:' . Builder::$defaultStringLength, 'unique:airports,name'],
            'location' => ['sometimes', 'required_without_all:name,timezoneOffset', 'string', 'regex:/^(-?([0-9]|[1-8][0-9]|9[0-9]|[12][0-9]{2}|3[0-5][0-9]|360)\.\d{1,6}),(?1)$/', 'unique:airports,location'],
            'timezoneOffset' => ['sometimes', 'required_without_all:name,location', 'integer', 'min:-12', 'max:14'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $airport = Airport::where('code', $request->input('code'))->first();
        $airport->fill($request->all());

        return new Resources\Airport($airport);
    }

    public function transporter(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => ['required', 'string', 'size:' . Transporter::CODE_LENGTH, 'exists:transporters,code'],
            'name' => ['required', 'string', 'max:' . Builder::$defaultStringLength, 'unique:transporters,name'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $transporter = Transporter::where('code', $request->input('code'))->first();
        $transporter->fill($request->all());

        return new Resources\Transporter($transporter);
    }

    public function flight(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'number' => ['required', 'string', 'max:' . Flight::NUMBER_LENGTH, 'regex:/^\d+$/', 'exists:flights,number'],
            'transporter' => ['sometimes', 'required_without_all:departureAirport,arrivalAirport,departureTime,arrivalTime', 'string', 'size:' . Transporter::CODE_LENGTH, 'exists:transporters,code'],
            'departureAirport'  => ['sometimes', 'required_without_all:transporter,arrivalAirport,departureTime,arrivalTime', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
            'arrivalAirport'    => ['sometimes', 'required_without_all:transporter,departureAirport,departureTime,arrivalTime', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
            'departureTime'     => ['sometimes', 'required_without_all:transporter,departureAirport,arrivalAirport,arrivalTime', 'date_format:Y-m-d H:i:s'],
            'arrivalTime'       => ['sometimes', 'required_without_all:transporter,departureAirport,arrivalAirport,departureTime', 'date_format:Y-m-d H:i:s']
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        if ($request->has(['departureTime', 'arrivalTime'])) {

            $departureTime = new \DateTime($request->input('departureTime'));
            $arrivalTime = new \DateTime($request->input('arrivalTime'));

            if ($departureTime >= $arrivalTime) {
                return [
                    'status' => false,
                    'message' => 'Departure time must less than arrival time.',
                ];
            }
        }

        if ($request->has(['departureAirport', 'arrivalAirport'])) {

            if ($request->input('departureAirport') === $request->input('arrivalAirport')) {
                return [
                    'status' => false,
                    'message' => 'Departure airport and arrival airport must be different.',
                ];
            }
        }

        $flight = Flight::where('number', $request->input('number'))->first();

        if ($request->has('departureTime') && !$request->has('arrivalTime')) {

            $departureTime = new \DateTime($request->input('departureTime'));

            if ($departureTime >= $flight->arrivalTime) {
                return [
                    'status' => false,
                    'message' => 'Departure time must less than current arrival time.',
                ];
            }
        }

        if ($request->has('arrivalTime') && !$request->has('departureTime')) {

            $arrivalTime = new \DateTime($request->input('arrivalTime'));

            if ($arrivalTime <= $flight->departureTime) {
                return [
                    'status' => false,
                    'message' => 'Arrival time must greater than current departure time.',
                ];
            }
        }

        if ($request->has('departureAirport')) {
            if ($request->input('departureAirport') === $flight->arrivalAirport->code) {
                return [
                    'status' => false,
                    'message' => 'Given departure airport is current arrival airport.',
                ];
            } else {
                $departureAirport = Airport::where('code', $request->input('departureAirport'))->first();
                $flight->departureAirportId = $departureAirport->id;
            }
        }

        if ($request->has('arrivalAirport')) {
            if ($request->input('arrivalAirport') === $flight->departureAirport->code) {
                return [
                    'status' => false,
                    'message' => 'Given arrival airport is current departure airport.',
                ];
            } else {
                $arrivalAirport = Airport::where('code', $request->input('departureAirport'))->first();
                $flight->arrivalAirportId = $arrivalAirport->id;
            }
        }

        $flight->fill($request->only([
            'departureTime', 'arrivalTime'
        ]));

        $flight->save();

        return new Resources\Flight($flight);
    }
}