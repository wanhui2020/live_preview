<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomeOk()
    {
        $response = $this->get('/system/login');

        $response->assertStatus(200);
    }
}
