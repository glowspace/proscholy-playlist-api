<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
