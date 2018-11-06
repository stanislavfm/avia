<?php
/**
 * Url Rewrite Import for Magento 1
 * @author Stanislav Chertilin <staschertilin@gmail.com>
 * @copyright 2018 https://github.com/stanislavfm/url-rewrite-import-m1
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources;
use App\Rules;
use App\AuthToken;

class AuthController extends Controller
{
    public function getToken(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'token' => ['required', 'string', 'size:' . AuthToken::TOKEN_LENGTH]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $hash = AuthToken::createHash($request->input('token'));
        $authToken = AuthToken::where('hash', $hash)->first();

        if (is_null($authToken)) {
            return [
                'status' => false,
                'message' => 'No token found.',
            ];
        }

        return new Resources\AuthToken($authToken);
    }

    public function createToken(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'permissions' => ['required', new Rules\Permissions()]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
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

        return new Resources\AuthToken($authToken);
    }

    public function updateToken(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'token' => ['required', 'string', 'size:' . AuthToken::TOKEN_LENGTH],
            'permissions' => ['required', new Rules\Permissions()]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $hash = AuthToken::createHash($request->input('token'));
        $authToken = AuthToken::where('hash', $hash)->first();

        if (is_null($authToken)) {
            return [
                'status' => false,
                'message' => 'No token found.',
            ];
        }

        $authToken->permissions = explode(',', $request->input('permissions'));
        $authToken->save();

        return new Resources\AuthToken($authToken);
    }

    public function deleteToken(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'token' => ['required', 'string', 'size:' . AuthToken::TOKEN_LENGTH]
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Validation failed.',
                'details' => $validator->errors()
            ];
        }

        $hash = AuthToken::createHash($request->input('token'));
        $authToken = AuthToken::where('hash', $hash)->first();

        if (is_null($authToken)) {
            return [
                'status' => false,
                'message' => 'No token found.',
            ];
        }

        try {
            $authToken->delete();
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        return [];
    }
}