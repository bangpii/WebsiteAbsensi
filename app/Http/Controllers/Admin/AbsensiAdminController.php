<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AbsensiAdminController extends Controller
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
     * LIST ABSENSI
     */
    public function index(Request $request)
    {
        try {

            $response = Http::timeout(30)
                ->withHeaders(
                    $this->authHeaders()
                )
                ->get(
                    "{$this->apiBase}/admin/absensi",
                    [
                        'status'   => $request->status,
                        'kelas'    => $request->kelas,
                        'tanggal'  => $request->tanggal,
                        'per_page' => $request->per_page ?? 20,
                    ]
                );

            if ($response->unauthorized()) {
                return redirect()->route('login');
            }

            $json = $response->json();

            return view('admin.absensi', [
                'absensis'   => $json['data']['data'] ?? [],
                'pagination' => $json['data'] ?? [],
                'error'      => null,
            ]);

        } catch (\Throwable $e) {

            return view('admin.absensi', [
                'absensis'   => [],
                'pagination' => [],
                'error'      => $e->getMessage(),
            ]);
        }
    }

    /**
     * DETAIL ABSENSI
     */
    public function show(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->get(
                "{$this->apiBase}/admin/absensi/{$id}"
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
     * UPDATE ABSENSI
     */
    public function update(
        Request $request,
        int $id
    ) {
        $request->validate([
            'status'     => 'required',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->put(
                "{$this->apiBase}/admin/absensi/{$id}",
                [
                    'status'     => $request->status,
                    'keterangan' => $request->keterangan,
                ]
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal update absensi'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Absensi berhasil diperbarui'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * HAPUS ABSENSI
     */
    public function destroy(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->delete(
                "{$this->apiBase}/admin/absensi/{$id}"
            );

            $json = $response->json();

            return back()->with(
                'success',
                $json['message']
                ?? 'Absensi berhasil dihapus'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * STATISTIK ABSENSI
     */
    public function statistik()
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->get(
                "{$this->apiBase}/admin/absensi/statistik"
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
