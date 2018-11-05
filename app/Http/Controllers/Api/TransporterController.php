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
use App\Transporter;

class TransporterController extends Controller
{
    public function getTransporters(Request $request)
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

    public function createTransporter(Request $request)
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

    public function updateTransporter(Request $request)
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

    public function deleteTransporter(Request $request)
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
}