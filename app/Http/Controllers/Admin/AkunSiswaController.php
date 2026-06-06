<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AkunSiswaController extends Controller
{
    /**
     * Base URL backend API absensi
     */
    protected string $apiBase;

    public function __construct()
    {
        $this->apiBase = config('services.absensi_api.url', env('ABSENSI_API_URL', 'http://127.0.0.1:8000/api'));
    }

    /**
     * Helper: ambil token admin dari session
     */
    protected function adminToken(): string
    {
        // FIX: AuthLoginAdminController nyimpan sebagai 'token', bukan 'admin_token'
        return session('token', '');
    }

    /**
     * Helper: header auth
     */
    protected function authHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->adminToken(),
            'Accept'        => 'application/json',
        ];
    }

    // ============================================================
    // INDEX — Tampilkan halaman Data Siswa
    // ============================================================

    public function index(Request $request)
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders($this->authHeaders())
                ->get("{$this->apiBase}/admin/user-siswa");

            if ($response->unauthorized()) {
                return redirect()->route('login')
                    ->withErrors(['session' => 'Sesi habis, silakan login kembali.']);
            }

            if (!$response->successful()) {
                return view('admin.akun-siswa', [
                    'siswaList' => collect(),
                    'total'     => 0,
                    'error'     => 'Gagal memuat data siswa dari server. (' . $response->status() . ')',
                ]);
            }

            $json     = $response->json();
            $rawData  = collect($json['data'] ?? []);

            // Normalisasi data siswa ke format yang dibutuhkan view
            $siswaList = $rawData->map(function ($item, $index) {
                $siswa = $item['siswa'] ?? [];
                return [
                    'no'        => $index + 1,
                    'user_id'   => $item['id'],
                    'nama'      => $item['name'] ?? '-',
                    'email'     => $item['email'] ?? '-',
                    'nisn'      => $siswa['nisn'] ?? '-',
                    'kelas'     => $siswa['kelas'] ?? '-',
                    'tingkat'   => $siswa['tingkat'] ?? '-',
                    'jurusan'   => $siswa['jurusan'] ?? '-',
                    'is_online' => $item['is_online'] ?? false,
                    'last_seen' => $item['last_seen'] ?? null,
                    'photo'     => $item['photo'] ?? null,
                ];
            });

            return view('admin.akun-siswa', [
                'siswaList' => $siswaList,
                'total'     => $json['total'] ?? $siswaList->count(),
                'error'     => null,
            ]);

        } catch (\Throwable $e) {
            return view('admin.akun-siswa', [
                'siswaList' => collect(),
                'total'     => 0,
                'error'     => 'Koneksi ke server gagal: ' . $e->getMessage(),
            ]);
        }
    }

    // ============================================================
    // SYNC — Trigger sinkronisasi siswa dari LMS
    // ============================================================

    public function sync(Request $request)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders($this->authHeaders())
                ->post("{$this->apiBase}/admin/user-siswa/sync");

            if ($response->unauthorized()) {
                return redirect()->route('login')
                    ->withErrors(['session' => 'Sesi habis, silakan login kembali.']);
            }

            $json = $response->json();

            if ($response->successful() && ($json['status'] ?? false)) {
                return redirect()->route('admin.akun-siswa')
                    ->with('success', "Sinkronisasi berhasil! {$json['total']} siswa berhasil disinkronkan dari LMS.");
            }

            return redirect()->route('admin.akun-siswa')
                ->with('error', $json['message'] ?? 'Sinkronisasi gagal.');

        } catch (\Throwable $e) {
            return redirect()->route('admin.akun-siswa')
                ->with('error', 'Koneksi ke server gagal: ' . $e->getMessage());
        }
    }

    // ============================================================
    // SHOW — Detail siswa (JSON untuk modal/AJAX)
    // ============================================================

    public function show(int $id)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->authHeaders())
                ->get("{$this->apiBase}/admin/user-siswa/{$id}");

            if (!$response->successful()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data siswa tidak ditemukan.',
                ], 404);
            }

            return response()->json($response->json());

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
