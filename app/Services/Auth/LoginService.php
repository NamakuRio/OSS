<?php

namespace App\Services\Auth;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoginService
{
    public function login(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        DB::beginTransaction();
        try {
            if ($this->attemptLogin($request)) {
                $request->session()->regenerate();

                $login_destination = auth()->user()->roles->first()->login_destination;
                DB::commit();
                return ['status' => 'success', 'message' => 'Berhasil masuk.', 'login_destination' => $login_destination];
            }

            DB::rollback();
            return ['status' => 'error', 'message' => 'Nama Pengguna atau Kata Sandi yang Anda masukkan salah.'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function loginApi(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return ['response' => ['status' => 'warning', 'message' => $validator->errors()->first()], 'code' => 401];
        }

        DB::beginTransaction();
        try {
            if (!$token = $this->attemptLogin($request, 'api')) {
                DB::rollback();
                return ['response' => ['status' => 'error', 'message' => 'Nama Pengguna atau Kata Sandi yang Anda masukkan salah.'], 'code' => 401];
            }

            DB::commit();
            return ['response' => ['status' => 'success', 'message' => 'Berhasil masuk.', 'data' => ['access_token' => $token, 'token_type' => 'bearer', 'expires_in' => ($this->guard('api')->factory()->getTTL() * 60)]], 'code' => 200];
        } catch (Exception $e) {
            DB::rollback();
            return ['response' => ['status' => 'error', 'message' => $e->getMessage()], 'code' => 401];
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return ['status' => 'success', 'message' => 'Berhasil keluar.'];
    }

    public function logoutApi()
    {
        $this->guard('api')->logout();

        return ['response' => ['status' => 'success', 'message' => 'Berhasil keluar.'], 'code' => 200];
    }

    protected function attemptLogin(Request $request, $guard = 'web')
    {
        if ($guard == 'web') {
            return $this->guard($guard)->attempt($this->credentials($request), $request->filled('remember'));
        }
        return $this->guard($guard)->attempt($this->credentials($request));
    }

    protected function credentials(Request $request)
    {
        return $request->only('username', 'password');
    }

    protected function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }

    protected function validator(array $data)
    {
        $rules = [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'string' => ':attribute harus berupa String'
        ];

        return Validator::make($data, $rules, $messages);
    }
}
