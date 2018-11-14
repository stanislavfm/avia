<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use App\AuthToken;

class AuthTest extends TestCase
{
    protected function getCommand()
    {
        return 'token';
    }

    public function testGetTokenWithInvalidParameters()
    {
        $requests = [
            [
                'parameters' => [],
                'response' => [
                    'messages' => ['token' => [__('validation.required', ['attribute' => 'token'])]]
                ]
            ],
            [
                'parameters' => [
                    'token' => ''
                ],
                'response' => [
                    'messages' => ['token' => [__('validation.required', ['attribute' => 'token'])]]
                ]
            ],
            [
                'parameters' => [
                    'token' => 0
                ],
                'response' => [
                    'messages' => ['token' => [
                        __('validation.string', ['attribute' => 'token']),
                        __('validation.size.string', ['attribute' => 'token', 'size' => AuthToken::TOKEN_LENGTH]),
                    ]]
                ]
            ],
            [
                'parameters' => [
                    'token' => 'test'
                ],
                'response' => [
                    'messages' => ['token' => [__('validation.size.string', ['attribute' => 'token', 'size' => AuthToken::TOKEN_LENGTH])]]
                ]
            ],
        ];

        foreach ($requests as $request) {

            $response = $this->json('get', $this->getUrl(), $request['parameters']);

            $this->assertResponse($response);

            $response
                ->assertStatus(Response::HTTP_BAD_REQUEST)
                ->assertJsonFragment([
                    'request' => [
                        'method' => 'get',
                        'command' => $this->getCommand(),
                        'parameters' => $request['parameters']
                    ],
                    'response' => $request['response']
                ]);
        }
    }

    public function testGetTokenWithNonexistentToken()
    {
        $request = [
            'parameters' => [
                'token' => str_random(AuthToken::TOKEN_LENGTH)
            ],
            'response' => [
                'messages' => [__('api.no_token_found')]
            ]
        ];

        $response = $this->json('get', $this->getUrl(), $request['parameters']);

        $this->assertResponse($response);

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment([
                'request' => [
                    'method' => 'get',
                    'command' => $this->getCommand(),
                    'parameters' => $request['parameters']
                ],
                'response' => $request['response']
            ]);
    }

    public function testCreateTokenWithInvalidParameters()
    {
        $permissionValidationError = __('api.permissions_validation', ['values' => implode(', ', AuthToken::PERMISSIONS)]);

        $requests = [
            [
                'parameters' => [
                    'permissions' => 'test'
                ],
                'response' => [
                    'messages' => ['permissions' => [
                        __('validation.array', ['attribute' => 'permissions']),
                        $permissionValidationError
                    ]]
                ]
            ],
            [
                'parameters' => [],
                'response' => [
                    'messages' => ['permissions' => [__('validation.required', ['attribute' => 'permissions'])]]
                ]
            ],
            [
                'parameters' => [
                    'permissions' => ['reload']
                ],
                'response' => [
                    'messages' => ['permissions' => [$permissionValidationError]]
                ]
            ],
            [
                'parameters' => [
                    'permissions' => [AuthToken::UPDATE_PERMISSION, 'drop']
                ],
                'response' => [
                    'messages' => ['permissions' => [$permissionValidationError]]
                ]
            ],
            [
                'parameters' => [
                    'permissions' => [AuthToken::DELETE_PERMISSION, AuthToken::DELETE_PERMISSION]
                ],
                'response' => [
                    'messages' => ['permissions' => [$permissionValidationError]]
                ]
            ],
        ];

        foreach ($requests as $request) {

            $response = $this->json('post', $this->getUrl(), $request['parameters']);

            $this->assertResponse($response);

            $response
                ->assertStatus(Response::HTTP_BAD_REQUEST)
                ->assertJsonFragment([
                    'request' => [
                        'method' => 'create',
                        'command' => $this->getCommand(),
                        'parameters' => $request['parameters']
                    ],
                    'response' => $request['response']
                ]);
        }
    }
}
