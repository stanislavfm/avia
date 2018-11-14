<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\AuthToken;

class ApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var JsonResponse $response */
        $response = $next($request);

        if (!$response instanceof JsonResponse) {
            return $response;
        }

        $apiResponseData = [
            'request'  => $this->getRequestData($request),
            'response' => $response->getData(),
            'version'  => config('api.version'),
            'hash'     => str_random(32)
        ];

        $apiResponse = new JsonResponse($apiResponseData);
        $apiResponse->setStatusCode($response->getStatusCode());

        return $apiResponse;
    }

    private function getRequestData(Request $request)
    {
        $action = $request->route()->getActionMethod();
        $permissionsPattern = implode('|', AuthToken::PERMISSIONS);
        $pattern = "/^($permissionsPattern)(\w+)$/";

        preg_match($pattern, $action, $matches);

        $method = isset($matches[1]) ? $matches[1] : 'unrecognized';
        $command = isset($matches[2]) ? $matches[2] : 'unrecognized';

        return [
            'method'     => $method,
            'command'    => strtolower($command),
            'parameters' => $request->json()->all()
        ];
    }
}
