<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class WebCrawlerTest extends TestCase
{
    public function testWhenUrlNotValidThenFail()
    {
        $response = $this->post('/', ['url' => 'invalid', 'depth' => '6']);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors(['url' => 'The url must be a valid URL.']);
    }

    public function testWhenDepthNotValidThenFail()
    {
        $response = $this->post('/', ['url' => 'https://laravel.com/', 'depth' => '100']);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors(['depth' => 'The depth must be between 1 and 6.']);
    }

    public function testWhenNoRequiredDataThenFail()
    {
        $response = $this->post('/', ['url' => '', 'depth' => '']);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors(['depth', 'url']);
    }

    public function testWhenValidDataThenSuccess()
    {
        $response = $this->post('/', ['url' => 'https://laravel.com/', 'depth' => 6]);

        $response->assertStatus(Response::HTTP_OK);
    }
}
