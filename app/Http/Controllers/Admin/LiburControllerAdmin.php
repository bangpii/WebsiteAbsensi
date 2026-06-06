<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LiburControllerAdmin extends Controller
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
     * LIST DATA LIBUR
     * ======================================================
     */
    public function index()
    {
        try {

            $response = Http::timeout(15)
                ->withHeaders($this->authHeaders())
                ->get("{$this->apiBase}/admin/liburs");

            if ($response->unauthorized()) {
                return redirect()->route('login');
            }

            $json = $response->json();

            return view('admin.libur', [
                'liburs' => collect($json['data'] ?? []),
                'error'  => null,
            ]);

        } catch (\Throwable $e) {

            return view('admin.libur', [
                'liburs' => collect(),
                'error'  => $e->getMessage(),
            ]);
        }
    }

    /**
     * ======================================================
     * SIMPAN LIBUR
     * ======================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|date',
            'nama'        => 'required|string|max:255',
            'keterangan'  => 'nullable|string|max:255',
            'tipe'        => 'required|in:nasional,sekolah,custom',
        ]);

        try {

            $response = Http::withHeaders($this->authHeaders())
                ->post("{$this->apiBase}/admin/liburs", [
                    'tanggal'    => $request->tanggal,
                    'nama'       => $request->nama,
                    'keterangan' => $request->keterangan,
                    'tipe'       => $request->tipe,
                ]);

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message'] ?? 'Gagal menambah libur'
                );
            }

            return back()->with(
                'success',
                $json['message'] ?? 'Libur berhasil ditambahkan'
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
     * UPDATE LIBUR
     * ======================================================
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'tanggal'     => 'required|date',
            'nama'        => 'required|string|max:255',
            'keterangan'  => 'nullable|string|max:255',
            'tipe'        => 'required|in:nasional,sekolah,custom',
        ]);

        try {

            $response = Http::withHeaders($this->authHeaders())
                ->put("{$this->apiBase}/admin/liburs/{$id}", [
                    'tanggal'    => $request->tanggal,
                    'nama'       => $request->nama,
                    'keterangan' => $request->keterangan,
                    'tipe'       => $request->tipe,
                ]);

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message'] ?? 'Gagal update libur'
                );
            }

            return back()->with(
                'success',
                $json['message'] ?? 'Libur berhasil diperbarui'
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
     * HAPUS LIBUR
     * ======================================================
     */
    public function destroy(int $id)
    {
        try {

            $response = Http::withHeaders($this->authHeaders())
                ->delete("{$this->apiBase}/admin/liburs/{$id}");

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message'] ?? 'Gagal menghapus libur'
                );
            }

            return back()->with(
                'success',
                $json['message'] ?? 'Libur berhasil dihapus'
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
     * TOGGLE STATUS
     * ======================================================
     */
    public function toggle(int $id)
    {
        try {

            $response = Http::withHeaders($this->authHeaders())
                ->post("{$this->apiBase}/admin/liburs/{$id}/toggle");

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message'] ?? 'Gagal mengubah status'
                );
            }

            return back()->with(
                'success',
                $json['message'] ?? 'Status berhasil diubah'
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
     * DETAIL SATU LIBUR (AJAX)
     * ======================================================
     */
    public function show(int $id)
    {
        try {

            $response = Http::withHeaders($this->authHeaders())
                ->get("{$this->apiBase}/admin/liburs/{$id}");

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
