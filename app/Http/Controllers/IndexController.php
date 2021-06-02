<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function index()
    {
        return [
            'message' => 'Portal ProScholy API server',
            'version' => '0.0.1',
            'environment' => config('app.env'),
            'code'    => 200,
        ];
    }
}
