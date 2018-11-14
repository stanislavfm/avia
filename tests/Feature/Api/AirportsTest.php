<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AirportsTest extends TestCase
{
    protected function getCommand()
    {
        return 'airports';
    }

}
