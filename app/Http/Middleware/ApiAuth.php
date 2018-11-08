<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Carbon\Carbon;
use Closure;
use App\AuthToken;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = \Validator::make($request->all(), [
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
                    'messages' => [__('api.token_not_exists')],
                ])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        if (Carbon::now() > $authToken->expiresAt) {
            return response()
                ->json([
                    'messages' => [__('api.token_is_expired')],
                ])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $requestMethodPermissionMap = [
            'get'    => AuthToken::GET_PERMISSION,
            'post'   => AuthToken::CREATE_PERMISSION,
            'put'    => AuthToken::UPDATE_PERMISSION,
            'delete' => AuthToken::DELETE_PERMISSION,
        ];

        $requestMethod = strtolower($request->getMethod());
        $neededPermission = $requestMethodPermissionMap[$requestMethod];

        if (!in_array($neededPermission, $authToken->permissions)) {
            return response()
                ->json([
                    'messages' => [__('api.token_no_permissions')],
                ])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
