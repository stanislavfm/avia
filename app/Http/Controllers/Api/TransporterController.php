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
use App\Transporter;

class TransporterController extends Controller
{
    public function getTransporters(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'name' => ['sometimes', 'required', 'string', 'min:1', 'max:' . Builder::$defaultStringLength],
            'code' => ['sometimes', 'required', 'string', 'size:' . Transporter::CODE_LENGTH]
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
                    'messages' => [__('api.one_query_parameter')]
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        if ($request->has('name')) {
            $transporters = Transporter::where('name', 'like', '%' . $request->input('name') . '%')->get();
        } elseif ($request->has('code')) {
            $transporters = Transporter::where('code', $request->input('code'))->get();
        } else {
            $transporters = Transporter::all();
        }

        if ($transporters->isEmpty()) {
            return response()
                ->json([
                    'messages' => [__('api.no_transporters_found')]
                ])
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return Resources\Transporter::collection($transporters);
    }

    public function createTransporter(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'code' => ['required', 'string', 'size:' . Transporter::CODE_LENGTH, 'unique:transporters,code'],
            'name' => ['required', 'string', 'max:' . Builder::$defaultStringLength, 'unique:transporters,name'],
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $transporter = Transporter::create($request->only([
            'code', 'name'
        ]));

        return response()
            ->json(new Resources\Transporter($transporter))
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function updateTransporter(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'code' => ['required', 'string', 'size:' . Transporter::CODE_LENGTH, 'exists:transporters,code'],
            'name' => ['required', 'string', 'max:' . Builder::$defaultStringLength, 'unique:transporters,name'],
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $transporter = Transporter::where('code', $request->input('code'))->first();
        $transporter->fill($request->input());

        return new Resources\Transporter($transporter);
    }

    public function deleteTransporter(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'code' => ['required', 'string', 'size:' . Transporter::CODE_LENGTH, 'exists:transporters,code'],
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        try {
            Transporter::where('code', $request->input('code'))->delete();
        } catch (\Exception $exception) {
            return response()
                ->json([
                    'messages' => [$exception->getMessage()],
                ])
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}