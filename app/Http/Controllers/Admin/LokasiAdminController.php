<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LokasiAdminController extends Controller
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
     * HALAMAN LOKASI
     */
    public function index()
    {
        try {

            $response = Http::timeout(20)
                ->withHeaders(
                    $this->authHeaders()
                )
                ->get(
                    "{$this->apiBase}/admin/lokasi"
                );

            if ($response->unauthorized()) {
                return redirect()->route('login');
            }

            $json = $response->json();

            return view('admin.lokasi', [
                'lokasi' => $json['data'] ?? null,
                'error'  => null,
            ]);

        } catch (\Throwable $e) {

            return view('admin.lokasi', [
                'lokasi' => null,
                'error'  => $e->getMessage(),
            ]);
        }
    }

    /**
     * DETAIL LOKASI
     */
    public function show()
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->get(
                "{$this->apiBase}/admin/lokasi/detail"
            );

            return response()->json(
                $response->json(),
                $response->status()
            );

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * UPDATE LOKASI
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'lat_min'     => 'required|numeric',
            'lat_max'     => 'required|numeric',
            'lng_min'     => 'required|numeric',
            'lng_max'     => 'required|numeric',
        ]);

        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->put(
                "{$this->apiBase}/admin/lokasi",
                [
                    'nama_lokasi' => $request->nama_lokasi,
                    'lat_min'     => $request->lat_min,
                    'lat_max'     => $request->lat_max,
                    'lng_min'     => $request->lng_min,
                    'lng_max'     => $request->lng_max,
                ]
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal update lokasi'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Lokasi berhasil diperbarui'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
