<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoverTest extends TestCase
{

    public function test_create_rover()
    {
        $response = $this->get('/rover/create?rover_name=rover1');
        $response->assertStatus(200);

        $this->assertEquals($response->baseResponse->original, 
        [
            'success' => true, 
            'message' =>'rover1 created'
        ]);
    }

    public function test_get_rover()
    {
        $create = $this->get('/rover/create?rover_name=rover1');

        $response = $this->get('/rover/get?rover_name=rover1');
        $response->assertStatus(200);

        $this->assertEquals($response->baseResponse->original, 
        [
            'rover_name' => 'rover1', 
            'location' => [0,0,'N']
        ]);
    }

    public function test_get_state_rover()
    {
        $create = $this->get('/rover/create?rover_name=rover1');
        
        $response = $this->get('/rover/getState?rover_name=rover1');
        $response->assertStatus(200);

        $this->assertEquals($response->baseResponse->original, 
        [
            'rover_name' => 'rover1', 
            'location' => [0,0,'N']
        ]);
    }

    public function test_send_command()
    {
        $createRover = $this->get('/rover/create?rover_name=rover1');
        $createPlateau = $this->get('/plateau/create?x=10&y=15');
        
        $response = $this->get('/rover/sendCommand?commands=LMLMLMLMM&rover_name=rover1');
        $response->assertStatus(200);

        $this->assertEquals($response->baseResponse->original, 
        [
            'success' => true, 
            'data' => 
            [
                'rover_name' => 'rover1', 
                'location' => [0,1,'N']
            ]
        ]);
    }

    public function test_send_command_double_rover()
    {
        $createRover = $this->get('/rover/create?rover_name=rover1');
        $createRover2 = $this->get('/rover/create?rover_name=rover2');
        $createPlateau = $this->get('/plateau/create?x=10&y=15');
        
        $response1 = $this->get('/rover/sendCommand?commands=LMLMLMLMM&rover_name=rover1');
        $response1->assertStatus(200);

        
        $this->assertEquals($response1->baseResponse->original, 
        [
            'success' => true, 
            'data' => 
            [
                'rover_name' => 'rover1', 
                'location' => [0,1,'N']
            ]
        ]);

        $response2 = $this->get('/rover/sendCommand?commands=LMLMLMLMMLLMM&rover_name=rover2');
        $response2->assertStatus(200);

        $this->assertEquals($response2->baseResponse->original, 
        [
            'success' => true, 
            'data' =>
            [
                'rover_name' => 'rover2', 
                'location' => [0,3,'S']
            ]
        ]);

        $response3 = $this->get('/rover/sendCommand?commands=LMLMLMLMM&rover_name=rover2');
        $response3->assertStatus(200);


        $this->assertEquals($response3->baseResponse->original, 
        [
            'success' => true, 
            'data' => 
            [
                'rover_name' => 'rover2',
                'location' => [2,6,'S']
            ]
        ]);

    }
}
