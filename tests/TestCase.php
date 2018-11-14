<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertResponse(TestResponse $response)
    {
        $response->assertJsonStructure([
            'request' => [
                'method',
                'command',
                'parameters'
            ],
            'response' => [],
            'version',
            'hash'
        ]);

        $this->assertRegExp('/^[a-z0-9]{32}$/i', $response->json('hash'));
        $this->assertEquals(config('api.version'), $response->json('version'));
    }

    protected function getUrl()
    {
        return 'api/' . $this->getCommand();
    }

    abstract protected function getCommand();

    protected function setUp()
    {
        parent::setUp();
    }
}
