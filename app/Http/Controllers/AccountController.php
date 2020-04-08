<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class AccountController extends Controller
{
    public function index()
    {
        return view('account');
    }

    public function update(Request $request, UserService $userService)
    {
        $request->merge(['id' => auth()->user()->id]);
        $update = $userService->update($request);

        return response()->json($update);
    }
}
