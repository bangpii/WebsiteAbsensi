<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IzinAdminController extends Controller
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
     * ======================================================
     * LIST IZIN SISWA
     * ======================================================
     */
    public function index()
    {
        try {

            $response = Http::timeout(20)
                ->withHeaders($this->authHeaders())
                ->get("{$this->apiBase}/admin/izin");

            if ($response->unauthorized()) {
                return redirect()->route('login');
            }

            $json = $response->json();

            return view('admin.izin', [
                'izins' => collect($json['data'] ?? []),
                'error' => null,
            ]);

        } catch (\Throwable $e) {

            return view('admin.izin', [
                'izins' => collect(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * ======================================================
     * DETAIL IZIN
     * ======================================================
     */
    public function show(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->get(
                "{$this->apiBase}/admin/izin/{$id}"
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
     * ======================================================
     * TERIMA / TOLAK IZIN
     * ======================================================
     */
    public function approve(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'pesan'  => 'nullable|string',
        ]);

        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->post(
                "{$this->apiBase}/admin/izin/{$id}/approve",
                [
                    'status' => $request->status,
                    'pesan'  => $request->pesan,
                ]
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                        ?? 'Gagal memproses izin'
                );
            }

            return back()->with(
                'success',
                $json['message']
                    ?? 'Izin berhasil diproses'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * ======================================================
     * BALAS CHAT SISWA
     * ======================================================
     */
    public function kirimPesan(Request $request, int $id)
    {
        $request->validate([
            'pesan' => 'required|string',
        ]);

        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->post(
                "{$this->apiBase}/admin/izin/{$id}/pesan",
                [
                    'pesan' => $request->pesan,
                ]
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                        ?? 'Gagal mengirim pesan'
                );
            }

            return back()->with(
                'success',
                'Pesan berhasil dikirim'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * ======================================================
     * TANDAI CHAT DIBACA
     * ======================================================
     */
    public function markAsRead(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->post(
                "{$this->apiBase}/admin/izin/{$id}/read"
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
