<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HealthTest extends TestCase
{
    public function testAppHealthStatusSuccess()
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK);
    }
}
