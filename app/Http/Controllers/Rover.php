<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Plateau;

class Rover extends Controller
{
    public function get(Request $request)
    {
        if($request->rover_name)
        {
            return $request->session()->get($request->rover_name);
        }else{
            return ['success' => false, 'message' => 'You must send a rover name'];
        }
    }

    public function create(Request $request)
    {
        if($request->rover_name)
        {
            $request->session()->put($request->rover_name, ['rover_name' => $request->rover_name, 'location' => [0,0,'N']]);
            
            return ['success' => true, 'message' => $request->rover_name .' created'];
        }else{
            return ['success' => false, 'message' => 'You must send a rover name'];
        }
    }

    public function getState(Request $request)
    {
        if($request->rover_name)
        {
            return $request->session()->get($request->rover_name);
        }else{
            return ['success' => false, 'message' => 'You must send a rover name'];
        }
    }

    public function sendCommand(Request $request)
    {
        if($request->commands && $request->rover_name)
        {
            $commands = str_split($request->commands);
            $roverInfo = $this->get($request);
            $plateauInfo = Plateau::get($request);
            
            if(empty($roverInfo)){
                return ['success' => false, 'message' => 'There is no rover by this name'];
            }else if(empty($roverInfo)){
                return ['success' => false, 'message' => 'There is no plateau'];
            }

            $data = $this->calculateLocation($commands, $roverInfo, $plateauInfo);

            $request->session()->put($roverInfo['rover_name'], ['rover_name' => $roverInfo['rover_name'], 'location' => [$data['x'],$data['y'],$data['direction']]]);

            $roverInfo = $this->get($request);

            return ['success' => true, 'data' => $roverInfo];
        }else{
            return ['success' => false, 'message' => 'You must send a command and rover name'];
        }
    }

    public function calculateLocation($commands, $roverInfo, $plateauInfo)
    {
        $directions = ['N', 'E', 'S', 'W'];

        $currentDirection = $roverInfo['location'][2];
        $x = $roverInfo['location'][0];
        $y = $roverInfo['location'][1];

        foreach($commands as $command){

            $key = array_search($currentDirection, $directions,true);

            switch ($command) {
                case 'L':
                    $currentDirection = $key == 0 ? $directions[3] : $directions[($key - 1)];
                    
                    break;
                case 'R':
                    $currentDirection = $key == 3 ? $directions[0] : $directions[($key + 1)];

                    break;
                case 'M':
                    if($currentDirection == 'W' || $currentDirection == 'E'){

                        $x = $currentDirection == 'W' && $x <= 0 ? $x - 1 : $x + 1;

                        if($x >= 0 && $x > $plateauInfo[0]){
                            $x = $plateauInfo[0];
                        }else if($x <= 0 && $x < -$plateauInfo[0]){
                            $x = $plateauInfo[0];
                        }
                    }else{
                        $y = $currentDirection == 'S' && $y <= 0 ? $y - 1 : $y + 1;

                        if($y >= 0 && $y > $plateauInfo[1]){
                            $y = $plateauInfo[1];
                        }else if($y <= 0 && $y < -$plateauInfo[1]){
                            $y = $plateauInfo[1];
                        }
                    }
                    
                    break;
            }
        }
        return ['x' => $x, 'y' => $y , 'direction' => $currentDirection];

    }
}
