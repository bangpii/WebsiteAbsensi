<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardAdminController extends Controller
{
    protected string $apiBase;

    public function __construct()
    {
        $this->apiBase = config(
            'services.absensi_api.url',
            env(
                'ABSENSI_API_URL',
                'http://127.0.0.1:8000/api'
            )
        );
    }

    protected function adminToken(): string
    {
        return session('token', '');
    }

    protected function authHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->adminToken(),
            'Accept'        => 'application/json',
        ];
    }

    /**
     * DASHBOARD ADMIN
     */
    public function index()
    {
        try {

            $response = Http::timeout(30)
                ->withHeaders(
                    $this->authHeaders()
                )
                ->get(
                    "{$this->apiBase}/admin/dashboard"
                );

            if ($response->unauthorized()) {
                return redirect()->route('login');
            }

            $json = $response->json();

            return view('admin.dashboard', [

                'summary' => $json['summary'] ?? [

                    'total_siswa' => 0,
                    'total_pengumuman_aktif' => 0,
                    'total_event_aktif' => 0,
                    'total_izin_hari_ini' => 0,

                ],

                /*
                |--------------------------------------------------------------------------
                | EVENT AKTIF
                |--------------------------------------------------------------------------
                */
                'events' => $json['events'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | ABSENSI TERBARU
                |--------------------------------------------------------------------------
                */
                'absensiTerbaru' => $json['absensi_terbaru'] ?? [],

                'error' => null,

            ]);

        } catch (\Throwable $e) {

            return view('admin.dashboard', [

                'summary' => [

                    'total_siswa' => 0,
                    'total_pengumuman_aktif' => 0,
                    'total_event_aktif' => 0,
                    'total_izin_hari_ini' => 0,

                ],

                'events' => [],

                'absensiTerbaru' => [],

                'error' => $e->getMessage(),

            ]);
        }
    }
}
