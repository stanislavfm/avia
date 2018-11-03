<?php
/**
 * Url Rewrite Import for Magento 1
 * @author Stanislav Chertilin <staschertilin@gmail.com>
 * @copyright 2018 https://github.com/stanislavfm/url-rewrite-import-m1
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Airport;
use App\Transporter;
use App\Flight;

class DeleteController extends Controller
{
    public function airport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => ['required', 'string', 'size:' . Airport::CODE_LENGTH, 'exists:airports,code'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        try {
            Airport::where('code', $request->input('code'))->delete();
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'message' => $exception->getMessage()
            ];
        }

        return [
            'status' => true
        ];
    }

    public function transporter(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => ['required', 'string', 'size:' . Transporter::CODE_LENGTH, 'exists:transporters,code'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        try {
            Transporter::where('code', $request->input('code'))->delete();
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'message' => $exception->getMessage()
            ];
        }

        return [
            'status' => true
        ];
    }

    public function flight(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'number' => ['required', 'string', 'max:' . Flight::NUMBER_LENGTH, 'regex:/^\d+$/', 'exists:flights,number'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        try {
            Flight::where('number', $request->input('number'))->delete();
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'message' => $exception->getMessage()
            ];
        }

        return [
            'status' => true
        ];
    }
}