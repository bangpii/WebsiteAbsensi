<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthLoginAdminController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required'],
            'password' => ['required']
        ]);

        $response = Http::post(
            env('ABSENSI_API_URL').'/login',
            [
                'login' => $request->login,
                'password' => $request->password,
            ]
        );

        if (!$response->successful()) {

            return back()->withErrors([
                'login' => $response->json('message')
            ]);
        }

        $data = $response->json();

        if (($data['user']['role'] ?? null) !== 'admin') {

            return back()->withErrors([
                'login' => 'Akun bukan admin'
            ]);
        }

        session([
            'token' => $data['token'],
            'user' => $data['user']
        ]);

        return redirect()
            ->route('admin.dashboard');
    }

    public function logout()
    {
        if (session()->has('token')) {

            Http::withToken(
                session('token')
            )->post(
                env('ABSENSI_API_URL').'/logout'
            );
        }

        session()->flush();

        return redirect()->route('login');
    }
}
