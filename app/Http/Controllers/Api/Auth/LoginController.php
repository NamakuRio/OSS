<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request, LoginService $loginService)
    {
        $login = $loginService->loginApi($request);

        return response()->json($login['response'], $login['code']);
    }

    public function logout(LoginService $loginService)
    {
        $logout = $loginService->logoutApi();

        return response()->json($logout['response'], $logout['code']);
    }
}
