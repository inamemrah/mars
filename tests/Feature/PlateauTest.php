<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlateauTest extends TestCase
{
    
    public function test_create_plateau()
    {
        $response = $this->get('/plateau/create?x=10&y=15');
        $response->assertStatus(200);

        $this->assertEquals($response->baseResponse->original, 
        [
            'success' => true, 
            'message' => 'plateau created'
        ]);
    }

    public function test_get_plateau()
    {
        $create = $this->get('/plateau/create?x=10&y=15');

        $response = $this->get('/plateau/get');
        $response->assertStatus(200);

        $this->assertEquals($response->baseResponse->original, [10,15]);
    }
}
