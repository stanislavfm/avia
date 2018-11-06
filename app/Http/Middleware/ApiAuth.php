<?php

namespace App\Http\Middleware;

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
                'message' => 'Given token is not exists.',
            ];
        }

        if (Carbon::now() > $authToken->expiresAt) {
            return [
                'status' => false,
                'message' => 'Given token is expired.',
            ];
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
            return [
                'status' => false,
                'message' => 'Given token does not have permission to this method.',
            ];
        }

        return $next($request);
    }
}
