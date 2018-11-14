<?php
/**
 * Url Rewrite Import for Magento 1
 * @author Stanislav Chertilin <staschertilin@gmail.com>
 * @copyright 2018 https://github.com/stanislavfm/url-rewrite-import-m1
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Schema\Builder;
use App\Http\Controllers\Controller;
use App\Http\Resources;
use App\Airport;

class AirportController extends Controller
{
    public function getAirports(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'name' => ['sometimes', 'required', 'string', 'min:1', 'max:' . Builder::$defaultStringLength],
            'code' => ['sometimes', 'required', 'string', 'size:' . Airport::CODE_LENGTH]
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        if ($request->has(['name', 'code'])) {
            return response()
                ->json([
                    'messages' => [__('api.one_query_parameter')],
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        if ($request->has('name')) {
            $airports = Airport::where('name', 'like', '%' . $request->input('name') . '%')->get();
        } elseif ($request->has('code')) {
            $airports = Airport::where('code', $request->input('code'))->get();
        } else {
            $airports = Airport::all();
        }

        if ($airports->isEmpty()) {
            return response()
                ->json([
                    'messages' => [__('api.no_airports_found')]
                ])
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return Resources\Airport::collection($airports);
    }

    public function createAirport(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'code' => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'unique:airports,code'],
            'name' => ['required', 'string', 'max:' . Builder::$defaultStringLength, 'unique:airports,name'],
            'location' => ['required', 'string', 'regex:/^(-?([0-9]|[1-8][0-9]|9[0-9]|[12][0-9]{2}|3[0-5][0-9]|360)\.\d{1,6}),(?1)$/', 'unique:airports,location'],
            'timezoneOffset' => ['required', 'integer', 'min:-12', 'max:14'],
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $airport = Airport::create($request->only([
            'code', 'name', 'location', 'timezoneOffset'
        ]));

        return response()
            ->json(new Resources\Airport($airport))
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function updateAirport(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'code' => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
            'name' => ['sometimes', 'required_without_all:location,timezoneOffset', 'string', 'max:' . Builder::$defaultStringLength, 'unique:airports,name'],
            'location' => ['sometimes', 'required_without_all:name,timezoneOffset', 'string', 'regex:/^(-?([0-9]|[1-8][0-9]|9[0-9]|[12][0-9]{2}|3[0-5][0-9]|360)\.\d{1,6}),(?1)$/', 'unique:airports,location'],
            'timezoneOffset' => ['sometimes', 'required_without_all:name,location', 'integer', 'min:-12', 'max:14'],
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $airport = Airport::where('code', $request->in('code'))->first();
        $airport->fill($request->input());

        return new Resources\Airport($airport);
    }

    public function deleteAirport(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'code' => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        try {
            Airport::where('code', $request->input('code'))->delete();
        } catch (\Exception $exception) {
            return response()
                ->json([
                    'messages' => [$exception->getMessage()]
                ])
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}