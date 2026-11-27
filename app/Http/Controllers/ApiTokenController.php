<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiTokenController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $token = $request->user()->createToken('Postman')->accessToken;

        return response()->view('collections.api-token', ['token' => $token])
            ->header('Cache-Control', 'private, no-store');
    }
}
