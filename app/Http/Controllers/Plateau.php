<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Plateau extends Controller
{
    public function get(Request $request)
    {
        return $request->session()->get('plateau');
    }

    //TODO platonun hangi büyüklüklerde olduğunu belirtiyoruz
    public function create(Request $request)
    {
        $request->session()->put('plateau', [$request->x, $request->y]);
        return ['success' => true, 'message' => 'plateau created'];
    }
}
