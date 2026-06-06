<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WaktuAdminController extends Controller
{
    protected string $apiBase;

    public function __construct()
    {
        $this->apiBase = config(
            'services.absensi_api.url',
            env('ABSENSI_API_URL', 'http://127.0.0.1:8000/api')
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
     * =====================================================
     * LIST WAKTU
     * =====================================================
     */
    public function index()
    {
        try {

            $response = Http::timeout(20)
                ->withHeaders($this->authHeaders())
                ->get("{$this->apiBase}/admin/waktus");

            if ($response->unauthorized()) {
                return redirect()->route('login');
            }

            $json = $response->json();

            return view('admin.waktu', [
                'waktus' => collect($json['data'] ?? []),
                'error'  => null,
            ]);

        } catch (\Throwable $e) {

            return view('admin.waktu', [
                'waktus' => collect(),
                'error'  => $e->getMessage(),
            ]);
        }
    }

    /**
     * =====================================================
     * DETAIL
     * =====================================================
     */
    public function show(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->get(
                "{$this->apiBase}/admin/waktus/{$id}"
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
     * =====================================================
     * CREATE
     * =====================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'             => 'required',
            'hari'             => 'required',
            'jam_masuk_mulai'  => 'required',
            'jam_pulang_mulai' => 'required',
            'batas_terlambat'  => 'required',
        ]);

        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->post(
                "{$this->apiBase}/admin/waktus",
                $request->only([
                    'nama',
                    'hari',
                    'jam_masuk_mulai',
                    'jam_pulang_mulai',
                    'batas_terlambat'
                ])
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal menambah waktu'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Waktu berhasil ditambahkan'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * =====================================================
     * UPDATE
     * =====================================================
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama'             => 'required',
            'hari'             => 'required',
            'jam_masuk_mulai'  => 'required',
            'jam_pulang_mulai' => 'required',
            'batas_terlambat'  => 'required',
        ]);

        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->put(
                "{$this->apiBase}/admin/waktus/{$id}",
                $request->only([
                    'nama',
                    'hari',
                    'jam_masuk_mulai',
                    'jam_pulang_mulai',
                    'batas_terlambat'
                ])
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal memperbarui waktu'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Waktu berhasil diperbarui'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * =====================================================
     * DELETE
     * =====================================================
     */
    public function destroy(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->delete(
                "{$this->apiBase}/admin/waktus/{$id}"
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal menghapus waktu'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Waktu berhasil dihapus'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * =====================================================
     * TOGGLE
     * =====================================================
     */
    public function toggle(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->post(
                "{$this->apiBase}/admin/waktus/{$id}/toggle"
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
}
