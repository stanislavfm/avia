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

class PostController extends Controller
{
    public function airport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'unique:airports,code'],
            'name' => ['required', 'string', 'max:' . Builder::$defaultStringLength, 'unique:airports,name'],
            'location' => ['required', 'string', 'regex:/^(-?([0-9]|[1-8][0-9]|9[0-9]|[12][0-9]{2}|3[0-5][0-9]|360)\.\d{1,6}),(?1)$/', 'unique:airports,location'],
            'timezoneOffset' => ['required', 'integer', 'min:-12', 'max:14'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $airport = Airport::create($request->only([
            'code', 'name', 'location', 'timezoneOffset'
        ]));

        return new Resources\Airport($airport);
    }

    public function transporter(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => ['required', 'string', 'size:' . Transporter::CODE_LENGTH, 'unique:transporters,code'],
            'name' => ['required', 'string', 'max:' . Builder::$defaultStringLength, 'unique:transporters,name'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $transporter = Transporter::create($request->only([
            'code', 'name'
        ]));

        return new Resources\Transporter($transporter);
    }

    public function flight(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'number' => ['required', 'string', 'max:' . Flight::NUMBER_LENGTH, 'regex:/^\d+$/', 'unique:flights,number'],
            'transporter' => ['required', 'string', 'size:' . Transporter::CODE_LENGTH, 'exists:transporters,code'],
            'departureAirport'  => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'different:arrivalAirport', 'exists:airports,code'],
            'arrivalAirport'    => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'different:departureAirport', 'exists:airports,code'],
            'departureTime'     => ['required', 'date_format:Y-m-d H:i:s', 'before:arrivalTime'],
            'arrivalTime'       => ['required', 'date_format:Y-m-d H:i:s', 'after:departureTime']
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $transporter = Transporter::where('code', $request->input('transporter'))->first();
        $departureAirport = Airport::where('code', $request->input('departureAirport'))->first();
        $arrivalAirport = Airport::where('code', $request->input('arrivalAirport'))->first();

        $flight = new Flight($request->only([
            'number', 'departureTime', 'arrivalTime'
        ]));

        $flight->transporterId = $transporter->id;
        $flight->departureAirportId = $departureAirport->id;
        $flight->arrivalAirportId = $arrivalAirport->id;

        $flight->save();

        return new Resources\Flight($flight);
    }
}