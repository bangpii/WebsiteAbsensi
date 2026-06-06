<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CmsEventAdminController extends Controller
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
            'Accept' => 'application/json',
        ];
    }

    /**
     * LIST EVENT
     */
    public function index()
    {
        try {

            $response = Http::timeout(20)
                ->withHeaders($this->authHeaders())
                ->get(
                    "{$this->apiBase}/admin/cms/events"
                );

            if ($response->unauthorized()) {
                return redirect()->route('login');
            }

            return view('admin.event', [
                'events' => collect(
                    $response->json() ?? []
                ),
                'error' => null,
            ]);

        } catch (\Throwable $e) {

            return view('admin.event', [
                'events' => collect(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * DETAIL EVENT
     */
    public function show(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->get(
                "{$this->apiBase}/admin/cms/events/{$id}"
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
     * CREATE EVENT
     */
    public function store(Request $request)
    {
        try {

            $http = Http::withHeaders(
                $this->authHeaders()
            );

            if ($request->hasFile('gambar')) {

                $http = $http->attach(
                    'gambar',
                    fopen(
                        $request->file('gambar')->getPathname(),
                        'r'
                    ),
                    $request->file('gambar')->getClientOriginalName()
                );
            }

            $response = $http->post(
                "{$this->apiBase}/admin/cms/events",
                [
                    'judul'           => $request->judul,
                    'deskripsi'       => $request->deskripsi,
                    'kategori'        => $request->kategori,
                    'warna'           => $request->warna,
                    'tanggal_mulai'   => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'is_active'       => $request->boolean('is_active'),
                    'urutan'          => $request->urutan ?? 0,
                ]
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal membuat event'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Event berhasil dibuat'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * UPDATE EVENT
     */
    public function update(Request $request, int $id)
    {
        try {

            $http = Http::withHeaders(
                $this->authHeaders()
            );

            if ($request->hasFile('gambar')) {

                $http = $http->attach(
                    'gambar',
                    fopen(
                        $request->file('gambar')->getPathname(),
                        'r'
                    ),
                    $request->file('gambar')->getClientOriginalName()
                );
            }

            $response = $http->send(
                'POST',
                "{$this->apiBase}/admin/cms/events/{$id}",
                [
                    'query' => [
                        '_method' => 'PUT'
                    ],
                    'multipart' => [
                        [
                            'name' => 'judul',
                            'contents' => $request->judul
                        ],
                        [
                            'name' => 'deskripsi',
                            'contents' => $request->deskripsi ?? ''
                        ],
                        [
                            'name' => 'kategori',
                            'contents' => $request->kategori ?? ''
                        ],
                        [
                            'name' => 'warna',
                            'contents' => $request->warna ?? '#2563EB'
                        ],
                        [
                            'name' => 'tanggal_mulai',
                            'contents' => $request->tanggal_mulai
                        ],
                        [
                            'name' => 'tanggal_selesai',
                            'contents' => $request->tanggal_selesai ?? ''
                        ],
                    ]
                ]
            );

            $json = $response->json();

            if (!$response->successful()) {

                return back()->with(
                    'error',
                    $json['message']
                    ?? 'Gagal update event'
                );
            }

            return back()->with(
                'success',
                $json['message']
                ?? 'Event berhasil diupdate'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * DELETE EVENT
     */
    public function destroy(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->delete(
                "{$this->apiBase}/admin/cms/events/{$id}"
            );

            return back()->with(
                'success',
                $response->json()['message']
                ?? 'Event berhasil dihapus'
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * TOGGLE EVENT
     */
    public function toggle(int $id)
    {
        try {

            $response = Http::withHeaders(
                $this->authHeaders()
            )->post(
                "{$this->apiBase}/admin/cms/events/{$id}/toggle"
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
