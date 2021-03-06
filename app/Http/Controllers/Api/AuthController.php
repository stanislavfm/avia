<?php
/**
 * Flight Searcher API
 * @author Stanislav Chertilin <staschertilin@gmail.com>
 * @copyright 2019 https://github.com/stanislavfm/flight-searcher
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources;
use App\Rules;
use App\AuthToken;

class AuthController extends Controller
{
    public function getToken(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'token' => ['required', 'string', 'size:' . AuthToken::TOKEN_LENGTH]
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $hash = AuthToken::createHash($request->input('token'));
        $authToken = AuthToken::where('hash', $hash)->first();

        if (is_null($authToken)) {
            return response()
                ->json([
                    'messages' => [__('api.no_token_found')]
                ])
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return new Resources\AuthToken($authToken);
    }

    public function createToken(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'permissions' => ['required', 'array', new Rules\Permissions()]
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $token = str_random(AuthToken::TOKEN_LENGTH);
        $hash = AuthToken::createHash($token);
        $permissions = explode(',', $request->input('permissions'));
        $expiresAt = Carbon::now()->modify(config('api.token_lifetime'));

        $authToken = new AuthToken([
            'hash' => $hash,
            'permissions' => $permissions,
            'expiresAt' => $expiresAt
        ]);

        $authToken->setToken($token)->save();

        return response()
            ->json(new Resources\AuthToken($authToken))
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function updateToken(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'token' => ['required', 'string', 'size:' . AuthToken::TOKEN_LENGTH],
            'permissions' => ['required', 'array', new Rules\Permissions()]
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $hash = AuthToken::createHash($request->input('token'));
        $authToken = AuthToken::where('hash', $hash)->first();

        if (is_null($authToken)) {
            return response()
                ->json([
                    'messages' => [__('api.no_token_found')]
                ])
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $authToken->permissions = explode(',', $request->input('permissions'));
        $authToken->save();

        return new Resources\AuthToken($authToken);
    }

    public function deleteToken(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'token' => ['required', 'string', 'size:' . AuthToken::TOKEN_LENGTH]
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'messages' => $validator->errors()
                ])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $hash = AuthToken::createHash($request->input('token'));
        $authToken = AuthToken::where('hash', $hash)->first();

        if (is_null($authToken)) {
            return response()
                ->json([
                    'messages' => [__('api.no_token_found')]
                ])
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        try {
            $authToken->delete();
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