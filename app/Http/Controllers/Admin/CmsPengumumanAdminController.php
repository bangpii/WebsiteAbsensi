<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CmsPengumumanAdminController extends Controller
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
     * LIST PENGUMUMAN
     * =====================================================
     */
    public function index()
    {
        try {

            $response = Http::timeout(20)
                ->withHeaders($this->authHeaders())
                ->get(
                    "{$this->apiBase}/admin/cms/pengumumans"
                );

            if ($response->unauthorized()) {
                return redirect()->route('login');
            }

            return view('admin.pengumuman', [
                'pengumumans' => collect(
                    $response->json() ?? []
                ),
                'error' => null,
            ]);

        } catch (\Throwable $e) {

            return view('admin.pengumuman', [
                'pengumumans' => collect(),
                'error' => $e->getMessage(),
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
                "{$this->apiBase}/admin/cms/pengumumans/{$id}"
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
            'judul' => 'required|string|max:255',
            'isi'   => 'required|string',
        ]);

        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->post(
                "{$this->apiBase}/admin/cms/pengumumans",
                [
                    'judul'      => $request->judul,
                    'isi'        => $request->isi,
                    'icon'       => $request->icon,
                    'warna'      => $request->warna,
                    'is_active'  => $request->boolean('is_active'),
                    'is_pinned'  => $request->boolean('is_pinned'),
                    'urutan'     => $request->urutan ?? 0,
                ]
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal membuat pengumuman'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Pengumuman berhasil dibuat'
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
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->put(
                "{$this->apiBase}/admin/cms/pengumumans/{$id}",
                [
                    'judul'      => $request->judul,
                    'isi'        => $request->isi,
                    'icon'       => $request->icon,
                    'warna'      => $request->warna,
                    'is_active'  => $request->boolean('is_active'),
                    'is_pinned'  => $request->boolean('is_pinned'),
                    'urutan'     => $request->urutan ?? 0,
                ]
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal update pengumuman'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Pengumuman berhasil diupdate'
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
                "{$this->apiBase}/admin/cms/pengumumans/{$id}"
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal menghapus pengumuman'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Pengumuman berhasil dihapus'
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
                "{$this->apiBase}/admin/cms/pengumumans/{$id}/toggle"
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
