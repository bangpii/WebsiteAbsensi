@extends('layouts.admin')

@section('title', 'Izin & Sakit Siswa')

@section('content')

{{-- ============================================================
     TOAST
     ============================================================ --}}
@if(session('success'))
<div id="toast-ok" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold" style="max-width:380px;animation:toastSlide .35s ease forwards;">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0"><i class='bx bx-check text-emerald-600 text-lg'></i></div>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-700"><i class='bx bx-x text-lg'></i></button>
</div>
@endif
@if(session('error'))
<div id="toast-err" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 bg-white border border-red-200 text-red-800 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold" style="max-width:380px;animation:toastSlide .35s ease forwards;">
    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0"><i class='bx bx-x text-red-600 text-lg'></i></div>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-700"><i class='bx bx-x text-lg'></i></button>
</div>
@endif

@if($error ?? null)
<div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-sm font-medium">
    <i class='bx bxs-error-circle text-xl'></i>
    <span>Gagal memuat data: {{ $error }}</span>
</div>
@endif

{{-- ============================================================
     HEADER
     ============================================================ --}}
<div class="mb-6" data-aos="fade-down">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Izin & Sakit Siswa</h1>
            <p class="text-slate-500 text-sm mt-0.5">Kelola pengajuan izin, verifikasi, dan komunikasi dengan siswa</p>
        </div>
        @php
            $izins       = $izins ?? collect();
            $totalPending = $izins->where('status', 'pending')->count();
            $totalIzins   = $izins->count();
            $totalDisetujui = $izins->where('status', 'disetujui')->count();
            $totalDitolak   = $izins->where('status', 'ditolak')->count();

            $totalUnread = 0;
            foreach ($izins as $iz) {
                foreach ($iz['pesans'] ?? [] as $p) {
                    if ($p['sender_type'] === 'siswa' && !$p['is_read']) $totalUnread++;
                }
            }
        @endphp
        @if($totalUnread > 0)
        <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-100 rounded-xl text-blue-700 text-sm font-bold">
            <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
            {{ $totalUnread }} pesan belum dibaca
        </div>
        @endif
    </div>
</div>

{{-- ============================================================
     STAT CARDS
     ============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="0">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <i class='bx bxs-envelope text-blue-600 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ $totalIzins }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Total Pengajuan</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="50">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
            <i class='bx bxs-time text-amber-500 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ $totalPending }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Menunggu Review</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-3">
            <i class='bx bxs-check-circle text-emerald-500 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ $totalDisetujui }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Disetujui</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm" data-aos="fade-up" data-aos-delay="150">
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center mb-3">
            <i class='bx bxs-x-circle text-red-500 text-xl'></i>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ $totalDitolak }}</p>
        <p class="text-xs text-slate-500 font-medium mt-0.5">Ditolak</p>
    </div>
</div>

{{-- ============================================================
     LAYOUT UTAMA: LIST + PANEL CHAT
     ============================================================ --}}
<div class="flex gap-5 relative" id="mainLayout">

    {{-- ========================
         KOLOM KIRI — DAFTAR IZIN
         ======================== --}}
    <div class="flex-1 min-w-0 transition-all duration-300" id="listColumn">

        {{-- Filter Bar --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm mb-4" data-aos="fade-up">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[180px]">
                    <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400'></i>
                    <input type="text" id="searchInput" placeholder="Cari nama, NISN, kelas..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 text-xs font-medium rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
                </div>
                <select id="filterStatus" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/20 text-slate-600">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>
                <select id="filterJenis" class="bg-slate-50 border border-slate-200 text-xs font-bold rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/20 text-slate-600">
                    <option value="">Semua Jenis</option>
                    <option value="sakit">Sakit</option>
                    <option value="izin">Izin</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                <button onclick="resetFilter()" class="text-slate-400 hover:text-slate-700 text-xs font-medium flex items-center gap-1 transition-colors">
                    <i class='bx bx-reset'></i> Reset
                </button>
                <span class="ml-auto text-xs text-slate-400 font-medium"><span id="rowCount">{{ $totalIzins }}</span> pengajuan</span>
            </div>
        </div>

        {{-- Daftar Izin — Card Elegan --}}
        <div class="space-y-3" id="izinList" data-aos="fade-up" data-aos-delay="50">

            @forelse($izins as $izin)
            @php
                $statusConfig = match($izin['status']) {
                    'disetujui' => [
                        'dot' => 'bg-emerald-500',
                        'badgeBg' => 'bg-emerald-50',
                        'badgeText' => 'text-emerald-700',
                        'badgeBorder' => 'border-emerald-200',
                        'icon' => 'bxs-check-circle',
                        'label' => 'Disetujui',
                        'accent' => 'bg-emerald-500',
                    ],
                    'ditolak' => [
                        'dot' => 'bg-red-500',
                        'badgeBg' => 'bg-red-50',
                        'badgeText' => 'text-red-700',
                        'badgeBorder' => 'border-red-200',
                        'icon' => 'bxs-x-circle',
                        'label' => 'Ditolak',
                        'accent' => 'bg-red-500',
                    ],
                    default => [
                        'dot' => 'bg-amber-500',
                        'badgeBg' => 'bg-amber-50',
                        'badgeText' => 'text-amber-700',
                        'badgeBorder' => 'border-amber-200',
                        'icon' => 'bxs-time',
                        'label' => 'Menunggu',
                        'accent' => 'bg-amber-500',
                    ],
                };

                $jenisConfig = match($izin['jenis_izin']) {
                    'sakit'    => ['bg-rose-50 text-rose-700 border-rose-200', 'bxs-plus-medical', 'Sakit'],
                    'izin'     => ['bg-blue-50 text-blue-700 border-blue-200', 'bxs-envelope', 'Izin'],
                    default    => ['bg-slate-50 text-slate-700 border-slate-200', 'bxs-info-circle', 'Lainnya'],
                };

                $initials = strtoupper(substr($izin['nama_lengkap'], 0, 1));
                $unreadCount = collect($izin['pesans'] ?? [])->where('sender_type', 'siswa')->where('is_read', false)->count();
                $lastPesan = collect($izin['pesans'] ?? [])->last();
                $tanggalIzin = \Carbon\Carbon::parse($izin['tanggal_izin'])->translatedFormat('d M Y');
                $tanggalDibuat = \Carbon\Carbon::parse($izin['created_at'])->diffForHumans();
            @endphp

            <div class="izin-card group bg-white rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300 cursor-pointer relative overflow-hidden"
                data-id="{{ $izin['id'] }}"
                data-status="{{ $izin['status'] }}"
                data-jenis="{{ $izin['jenis_izin'] }}"
                data-search="{{ strtolower($izin['nama_lengkap'] . ' ' . ($izin['nisn'] ?? '') . ' ' . ($izin['kelas'] ?? '') . ' ' . ($izin['jurusan'] ?? '')) }}"
                onclick="openChat({{ $izin['id'] }})">

                {{-- Top accent line --}}
                <div class="h-0.5 w-full {{ $statusConfig['accent'] }}"></div>

                <div class="p-5">
                    {{-- Row 1: Avatar + Info + Meta --}}
                    <div class="flex items-start gap-4">
                        {{-- Avatar --}}
                        <div class="relative flex-shrink-0">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-base text-white shadow-md
                                {{ $izin['jenis_izin'] === 'sakit' ? 'bg-gradient-to-br from-rose-500 to-pink-700' : ($izin['jenis_izin'] === 'izin' ? 'bg-gradient-to-br from-blue-500 to-indigo-700' : 'bg-gradient-to-br from-slate-500 to-slate-700') }}">
                                {{ $initials }}
                            </div>
                            @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center border-2 border-white shadow-sm">{{ $unreadCount }}</span>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-900 text-sm">{{ $izin['nama_lengkap'] }}</h3>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5 tracking-wide">
                                @if($izin['nisn']) NISN: {{ $izin['nisn'] }} &bull; @endif
                                {{ $izin['kelas'] ?? '-' }}
                                @if($izin['jurusan']) &bull; {{ $izin['jurusan'] }} @endif
                            </p>
                        </div>

                        {{-- Right: Time + Status Dot --}}
                        <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                            <span class="text-[10px] text-slate-400 font-medium">{{ $tanggalDibuat }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full {{ $statusConfig['dot'] }}"></span>
                                <span class="text-[10px] font-bold {{ $statusConfig['badgeText'] }}">{{ $statusConfig['label'] }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Last Message Preview --}}
                    @if($lastPesan)
                    <div class="mt-3 pl-16">
                        <div class="bg-slate-50 rounded-xl px-3.5 py-2 border border-slate-100">
                            <p class="text-xs text-slate-500 truncate">
                                <span class="font-semibold {{ $lastPesan['sender_type'] === 'admin' ? 'text-blue-600' : 'text-slate-700' }}">
                                    {{ $lastPesan['sender_type'] === 'admin' ? 'Anda' : $izin['nama_lengkap'] }}:
                                </span>
                                <span class="text-slate-500">{{ $lastPesan['pesan'] }}</span>
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Row 3: Footer Bar --}}
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        {{-- Left: Tanggal + Jenis Badge --}}
                        <div class="flex items-center gap-2.5">
                            <div class="flex items-center gap-1.5 text-slate-500">
                                <i class='bx bx-calendar text-slate-400 text-xs'></i>
                                <span class="text-[11px] font-medium text-slate-600">{{ $tanggalIzin }}</span>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg border text-[10px] font-bold {{ $jenisConfig[0] }}">
                                <i class='bx {{ $jenisConfig[1] }} text-xs'></i>
                                {{ $jenisConfig[2] }}
                            </span>
                        </div>

                        {{-- Right: Actions --}}
                        <div class="flex items-center gap-2">
                            @if($izin['status'] === 'pending')
                            <div class="flex items-center gap-1.5" onclick="event.stopPropagation()">
                                <form action="{{ route('admin.izin.approve', $izin['id']) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="disetujui">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-500 text-white text-[10px] font-bold rounded-lg hover:bg-emerald-600 transition-all active:scale-95 shadow-sm shadow-emerald-200">
                                        <i class='bx bx-check text-sm'></i> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.izin.approve', $izin['id']) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-white text-red-600 border border-red-200 text-[10px] font-bold rounded-lg hover:bg-red-50 transition-all active:scale-95">
                                        <i class='bx bx-x text-sm'></i> Tolak
                                    </button>
                                </form>
                            </div>
                            @endif

                            <button onclick="openChat({{ $izin['id'] }}); event.stopPropagation();"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-[10px] font-bold rounded-lg hover:bg-blue-700 transition-all active:scale-95 shadow-sm shadow-blue-200">
                                <i class='bx bx-chat text-sm'></i>
                                Chat
                                @if($unreadCount > 0)
                                <span class="w-4 h-4 rounded-full bg-white text-blue-600 text-[9px] flex items-center justify-center font-extrabold">{{ $unreadCount }}</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-slate-100 py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                        <i class='bx bx-envelope-open text-3xl text-slate-300'></i>
                    </div>
                    <p class="text-slate-400 font-medium text-sm">Belum ada pengajuan izin</p>
                </div>
            </div>
            @endforelse

            {{-- No results --}}
            <div id="noResult" class="hidden bg-white rounded-2xl border border-slate-100 py-14 text-center">
                <i class='bx bx-search text-3xl text-slate-300 block mb-2'></i>
                <p class="text-slate-400 text-sm font-medium">Tidak ada yang cocok</p>
                <button onclick="resetFilter()" class="text-blue-600 text-xs font-bold hover:underline mt-1">Reset filter</button>
            </div>
        </div>
    </div>

    {{-- ========================
         PANEL CHAT (KANAN)
         ======================== --}}
    <div id="chatPanel"
        class="hidden flex-col bg-white rounded-3xl border border-slate-100 shadow-lg overflow-hidden transition-all duration-300"
        style="width:420px; flex-shrink:0; height:calc(100vh - 200px); position:sticky; top:80px;">

        {{-- Header Chat --}}
        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3 flex-shrink-0 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-bold text-white text-sm flex-shrink-0" id="chatAvatar">-</div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-white text-sm truncate" id="chatNama">-</p>
                <p class="text-blue-200 text-xs truncate" id="chatMeta">-</p>
            </div>
            <div class="flex items-center gap-2">
                <span id="chatStatusBadge" class="px-2 py-1 rounded-lg text-[10px] font-bold bg-white/20 text-white"></span>
                <button onclick="closeChat()" class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition-all">
                    <i class='bx bx-x text-lg'></i>
                </button>
            </div>
        </div>

        {{-- Info Strip --}}
        <div id="chatInfoStrip" class="px-5 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center gap-4 flex-shrink-0">
            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                <i class='bx bx-calendar text-slate-400'></i>
                <span id="chatTanggal">-</span>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                <i class='bx bxs-notepad text-slate-400'></i>
                <span id="chatKeterangan" class="truncate max-w-[160px]">-</span>
            </div>
        </div>

        {{-- Approve / Tolak strip --}}
        <div id="chatApproveStrip" class="hidden px-5 py-3 border-b border-amber-100 bg-amber-50/80 flex-shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse flex-shrink-0"></div>
                <span class="text-xs font-bold text-amber-700 flex-1">Izin ini menunggu persetujuan Anda</span>
                <form id="formSetujui" action="" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="disetujui">
                    <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white text-[10px] font-bold rounded-lg hover:bg-emerald-600 transition-all active:scale-95">
                        <i class='bx bx-check'></i> Setujui
                    </button>
                </form>
                <form id="formTolak" action="" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="ditolak">
                    <button type="submit" class="px-3 py-1.5 bg-white border border-red-200 text-red-600 text-[10px] font-bold rounded-lg hover:bg-red-50 transition-all active:scale-95">
                        <i class='bx bx-x'></i> Tolak
                    </button>
                </form>
            </div>
        </div>

        {{-- Pesan-pesan chat --}}
        <div id="chatMessages" class="flex-1 overflow-y-auto p-5 space-y-3">
        </div>

        {{-- Input balas --}}
        <div class="px-4 py-3 border-t border-slate-100 flex-shrink-0 bg-white">
            <form id="formPesan" action="" method="POST">
                @csrf
                <div class="flex items-end gap-2">
                    <div class="flex-1 bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-400 transition-all">
                        <textarea name="pesan" id="inputPesan" rows="1" placeholder="Ketik balasan untuk siswa..." class="w-full text-sm text-slate-700 bg-transparent outline-none resize-none placeholder-slate-400 max-h-24" style="min-height:20px;"></textarea>
                    </div>
                    <button type="submit" id="btnKirim"
                        class="w-10 h-10 rounded-xl bg-blue-600 hover:bg-blue-700 flex items-center justify-center text-white transition-all active:scale-95 shadow-md shadow-blue-200 flex-shrink-0">
                        <i class='bx bx-send text-base'></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- ============================================================
     MODAL APPROVE DENGAN PESAN CUSTOM
     ============================================================ --}}
<div id="modalApprove" class="fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalApprove')"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md z-10" style="animation:modalPop .3s cubic-bezier(.34,1.4,.64,1) forwards;">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class='bx bxs-check-circle text-emerald-600 text-lg'></i>
            </div>
            <h3 class="font-bold text-slate-900">Setujui Izin</h3>
            <button onclick="closeModal('modalApprove')" class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all"><i class='bx bx-x text-xl'></i></button>
        </div>
        <form id="formApproveModal" action="" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="status" value="disetujui">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Pesan untuk Siswa <span class="text-slate-400 font-normal">(opsional)</span></label>
                <textarea name="pesan" rows="3" placeholder="Contoh: Izin kamu disetujui. Segera bawa surat dokter saat masuk..."
                    class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all resize-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('modalApprove')" class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-emerald-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition-all active:scale-95">
                    <i class='bx bx-check mr-1'></i> Setujui Izin
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tolak --}}
<div id="modalTolak" class="fixed inset-0 z-[9998] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modalTolak')"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md z-10" style="animation:modalPop .3s cubic-bezier(.34,1.4,.64,1) forwards;">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center">
                <i class='bx bxs-x-circle text-red-600 text-lg'></i>
            </div>
            <h3 class="font-bold text-slate-900">Tolak Izin</h3>
            <button onclick="closeModal('modalTolak')" class="ml-auto w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all"><i class='bx bx-x text-xl'></i></button>
        </div>
        <form id="formTolakModal" action="" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="status" value="ditolak">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Alasan Penolakan <span class="text-slate-400 font-normal">(opsional)</span></label>
                <textarea name="pesan" rows="3" placeholder="Contoh: Izin ditolak karena data tidak lengkap..."
                    class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl p-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all resize-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('modalTolak')" class="flex-1 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-200 hover:bg-red-600 transition-all active:scale-95">
                    <i class='bx bx-x mr-1'></i> Tolak Izin
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes toastSlide {
        from { opacity:0; transform:translateX(20px); }
        to   { opacity:1; transform:translateX(0); }
    }
    @keyframes modalPop {
        from { opacity:0; transform:scale(.95) translateY(8px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }
    @keyframes msgIn {
        from { opacity:0; transform:translateY(6px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .izin-card.active-chat {
        border-color: #2563EB !important;
        box-shadow: 0 0 0 3px rgba(37,99,235,.10), 0 10px 25px -5px rgba(37,99,235,.15) !important;
    }

    #chatMessages::-webkit-scrollbar { width:4px; }
    #chatMessages::-webkit-scrollbar-thumb { background:rgba(148,163,184,.3); border-radius:4px; }

    #inputPesan { overflow-y:hidden; }
</style>

<script>
const IZIN_DATA = {!! json_encode($izins->values()) !!};
let activeChatId = null;

function applyFilter() {
    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const status = document.getElementById('filterStatus').value;
    const jenis  = document.getElementById('filterJenis').value;

    const cards = document.querySelectorAll('.izin-card');
    let vis = 0;

    cards.forEach(c => {
        const s = !status || c.dataset.status === status;
        const j = !jenis  || c.dataset.jenis  === jenis;
        const t = !search || c.dataset.search.includes(search);
        const show = s && j && t;
        c.style.display = show ? '' : 'none';
        if (show) vis++;
    });

    document.getElementById('noResult').classList.toggle('hidden', vis > 0 || cards.length === 0);
    document.getElementById('rowCount').textContent = vis;
}

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterJenis').value  = '';
    applyFilter();
}

document.getElementById('searchInput').addEventListener('input', applyFilter);
document.getElementById('filterStatus').addEventListener('change', applyFilter);
document.getElementById('filterJenis').addEventListener('change', applyFilter);

function openChat(izinId) {
    const izin = IZIN_DATA.find(i => i.id == izinId);
    if (!izin) return;

    activeChatId = izinId;

    document.querySelectorAll('.izin-card').forEach(c => c.classList.remove('active-chat'));
    const activeCard = document.querySelector(`.izin-card[data-id="${izinId}"]`);
    if (activeCard) activeCard.classList.add('active-chat');

    const panel = document.getElementById('chatPanel');
    panel.classList.remove('hidden');
    panel.classList.add('flex');

    document.getElementById('chatAvatar').textContent = izin.nama_lengkap.charAt(0).toUpperCase();
    document.getElementById('chatNama').textContent   = izin.nama_lengkap;
    document.getElementById('chatMeta').textContent   = [izin.nisn, izin.kelas, izin.jurusan].filter(Boolean).join(' • ');

    const sBadge = document.getElementById('chatStatusBadge');
    sBadge.textContent = { disetujui:'✓ Disetujui', ditolak:'✗ Ditolak', pending:'⏳ Pending' }[izin.status] || izin.status;

    const tanggal = new Date(izin.tanggal_izin);
    document.getElementById('chatTanggal').textContent = tanggal.toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });
    document.getElementById('chatKeterangan').textContent = izin.keterangan || '—';

    const strip = document.getElementById('chatApproveStrip');
    if (izin.status === 'pending') {
        strip.classList.remove('hidden');
        strip.classList.add('flex');
        document.getElementById('formSetujui').action = `/admin/izin/${izinId}/approve`;
        document.getElementById('formTolak').action   = `/admin/izin/${izinId}/approve`;
    } else {
        strip.classList.add('hidden');
        strip.classList.remove('flex');
    }

    document.getElementById('formPesan').action = `/admin/izin/${izinId}/pesan`;

    renderMessages(izin.pesans || [], izin.nama_lengkap);

    markRead(izinId);
}

function closeChat() {
    document.getElementById('chatPanel').classList.add('hidden');
    document.getElementById('chatPanel').classList.remove('flex');
    document.querySelectorAll('.izin-card').forEach(c => c.classList.remove('active-chat'));
    activeChatId = null;
}

function renderMessages(pesans, namaSiswa) {
    const box = document.getElementById('chatMessages');

    if (!pesans.length) {
        box.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full text-center py-10">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                    <i class='bx bx-message-detail text-2xl text-slate-300'></i>
                </div>
                <p class="text-slate-400 text-xs font-medium">Belum ada pesan</p>
            </div>`;
        return;
    }

    let html = '';
    pesans.forEach((p, idx) => {
        const isAdmin = p.sender_type === 'admin';
        const time    = new Date(p.created_at).toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
        const dateStr = new Date(p.created_at).toLocaleDateString('id-ID', { day:'numeric', month:'short' });

        const prevDate = idx > 0 ? new Date(pesans[idx-1].created_at).toDateString() : null;
        const curDate  = new Date(p.created_at).toDateString();
        if (prevDate !== curDate) {
            html += `<div class="flex items-center gap-2 my-2">
                <div class="flex-1 h-px bg-slate-100"></div>
                <span class="text-[10px] text-slate-400 font-medium px-2">${dateStr}</span>
                <div class="flex-1 h-px bg-slate-100"></div>
            </div>`;
        }

        if (isAdmin) {
            html += `
            <div class="flex justify-end" style="animation:msgIn .2s ease forwards; animation-delay:${idx * 0.03}s; opacity:0;">
                <div class="max-w-[80%]">
                    <div class="bg-blue-600 text-white text-sm px-4 py-2.5 rounded-2xl rounded-tr-md shadow-sm shadow-blue-200">
                        ${escHtml(p.pesan)}
                    </div>
                    <div class="flex items-center justify-end gap-1 mt-1 pr-1">
                        <span class="text-[10px] text-slate-400">${time}</span>
                        <i class='bx ${p.is_read ? "bx-check-double text-blue-500" : "bx-check text-slate-400"} text-xs'></i>
                    </div>
                </div>
            </div>`;
        } else {
            const initials = (p.sender?.name || namaSiswa || '?').charAt(0).toUpperCase();
            html += `
            <div class="flex items-end gap-2" style="animation:msgIn .2s ease forwards; animation-delay:${idx * 0.03}s; opacity:0;">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">${initials}</div>
                <div class="max-w-[80%]">
                    <div class="bg-slate-50 border border-slate-100 text-slate-700 text-sm px-4 py-2.5 rounded-2xl rounded-bl-md">
                        ${escHtml(p.pesan)}
                    </div>
                    <span class="text-[10px] text-slate-400 pl-1 mt-1 block">${time}</span>
                </div>
            </div>`;
        }
    });

    box.innerHTML = html;
    setTimeout(() => { box.scrollTop = box.scrollHeight; }, 60);
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#39;')
        .replace(/\n/g,'<br>');
}

function markRead(izinId) {
    fetch(`/admin/izin/${izinId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    }).catch(() => {});
}

function openModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('hidden');
    el.classList.add('flex');
}
function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.add('hidden');
    el.classList.remove('flex');
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeModal('modalApprove');
        closeModal('modalTolak');
        closeChat();
    }
});

const inputPesan = document.getElementById('inputPesan');
if (inputPesan) {
    inputPesan.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 96) + 'px';
    });

    inputPesan.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('formPesan').dispatchEvent(new Event('submit', { bubbles:true }));
        }
    });
}

const formPesan = document.getElementById('formPesan');
if (formPesan) {
    formPesan.addEventListener('submit', async function(e) {
        e.preventDefault();

        const text = inputPesan.value.trim();
        if (!text || !activeChatId) return;

        const btn = document.getElementById('btnKirim');
        btn.disabled = true;
        btn.innerHTML = '<i class="bx bx-loader-alt animate-spin text-base"></i>';

        try {
            const fd = new FormData();
            fd.append('pesan', text);
            fd.append('_token', '{{ csrf_token() }}');

            const resp = await fetch(`/admin/izin/${activeChatId}/pesan`, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (resp.ok || resp.redirected) {
                const now   = new Date();
                const time  = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
                const box   = document.getElementById('chatMessages');
                const div   = document.createElement('div');
                div.className = 'flex justify-end';
                div.style.cssText = 'animation:msgIn .2s ease forwards;';
                div.innerHTML = `
                    <div class="max-w-[80%]">
                        <div class="bg-blue-600 text-white text-sm px-4 py-2.5 rounded-2xl rounded-tr-md shadow-sm shadow-blue-200">${escHtml(text)}</div>
                        <div class="flex items-center justify-end gap-1 mt-1 pr-1">
                            <span class="text-[10px] text-slate-400">${time}</span>
                            <i class='bx bx-check text-slate-400 text-xs'></i>
                        </div>
                    </div>`;
                box.appendChild(div);
                box.scrollTop = box.scrollHeight;

                const izin = IZIN_DATA.find(i => i.id == activeChatId);
                if (izin) {
                    izin.pesans = izin.pesans || [];
                    izin.pesans.push({
                        id: Date.now(),
                        izin_id: activeChatId,
                        sender_id: 0,
                        sender_type: 'admin',
                        pesan: text,
                        is_read: false,
                        read_at: null,
                        created_at: now.toISOString(),
                    });
                }

                const card = document.querySelector(`.izin-card[data-id="${activeChatId}"]`);
                if (card) {
                    const preview = card.querySelector('.bg-slate-50 p.truncate, .bg-slate-50 p');
                    if (preview) {
                        preview.innerHTML = `<span class="font-semibold text-blue-600">Anda:</span> <span class="text-slate-500">${escHtml(text)}</span>`;
                    }
                }

                inputPesan.value = '';
                inputPesan.style.height = 'auto';
            }
        } catch (err) {
            console.error(err);
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="bx bx-send text-base"></i>';
    });
}

setTimeout(() => {
    ['toast-ok','toast-err'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.remove();
    });
}, 5000);
</script>

@endsection
