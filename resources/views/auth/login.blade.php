<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SMKN 8 Medan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%);
            min-height: 100vh;
            overflow: hidden;
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            animation: floatOrb 8s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: #60a5fa; top: -150px; left: -150px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: #818cf8; bottom: -100px; right: -100px; animation-delay: 3s; }
        .orb-3 { width: 300px; height: 300px; background: #34d399; top: 50%; right: 20%; animation-delay: 5s; }

        @keyframes floatOrb {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* Grid pattern overlay */
        .grid-bg {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Card glass */
        .glass-card {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow:
                0 32px 64px rgba(0,0,0,0.4),
                0 0 0 1px rgba(255,255,255,0.05) inset,
                0 1px 0 rgba(255,255,255,0.1) inset;
        }

        /* Input style */
        .input-field {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.15);
            color: white;
            transition: all 0.3s ease;
        }
        .input-field::placeholder { color: rgba(255,255,255,0.35); }
        .input-field:focus {
            outline: none;
            background: rgba(255,255,255,0.12);
            border-color: rgba(96,165,250,0.7);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2), 0 0 20px rgba(59,130,246,0.1);
        }

        /* Submit button */
        .btn-submit {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 8px 24px rgba(59,130,246,0.4), 0 1px 0 rgba(255,255,255,0.1) inset;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }
        .btn-submit:hover::before { left: 100%; }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(59,130,246,0.5), 0 1px 0 rgba(255,255,255,0.1) inset;
        }
        .btn-submit:active { transform: translateY(0); }

        /* Logo pulse */
        .logo-ring {
            animation: logoRing 3s ease-in-out infinite;
        }
        @keyframes logoRing {
            0%, 100% { box-shadow: 0 0 0 0 rgba(96,165,250,0.4); }
            50% { box-shadow: 0 0 0 12px rgba(96,165,250,0); }
        }

        /* Animate in */
        .card-appear {
            animation: cardAppear 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Stagger children */
        .stagger > * { opacity: 0; animation: fadeUp 0.5s ease forwards; }
        .stagger > *:nth-child(1) { animation-delay: 0.15s; }
        .stagger > *:nth-child(2) { animation-delay: 0.25s; }
        .stagger > *:nth-child(3) { animation-delay: 0.35s; }
        .stagger > *:nth-child(4) { animation-delay: 0.45s; }
        .stagger > *:nth-child(5) { animation-delay: 0.55s; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Error shake */
        .shake { animation: shake 0.4s ease; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }

        /* Loading spinner */
        .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Eye toggle */
        .eye-btn { transition: color 0.2s; }
        .eye-btn:hover { color: rgba(255,255,255,0.9); }
    </style>
</head>

<body class="relative flex items-center justify-center min-h-screen p-4">

    <!-- Background effects -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="grid-bg"></div>

    <!-- Decorative floating cards (blur background) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-12 left-12 w-48 h-28 glass-card rounded-2xl opacity-30 rotate-6"></div>
        <div class="absolute bottom-16 right-16 w-40 h-24 glass-card rounded-2xl opacity-20 -rotate-3"></div>
        <div class="absolute top-1/3 right-12 w-32 h-32 glass-card rounded-full opacity-15"></div>
    </div>

    <!-- Login Card -->
    <div class="glass-card rounded-3xl w-full max-w-md p-8 relative z-10 card-appear">

        <div class="stagger">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-5">
                    <div class="logo-ring w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-700 flex items-center justify-center shadow-2xl">
                        <i class='bx bxs-graduation text-white text-3xl'></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Selamat Datang</h1>
                <p class="text-blue-200/70 text-sm mt-1.5 font-medium">Panel Admin — SMKN 8 Medan</p>
            </div>

            <!-- Error Alert -->
            @if ($errors->any())
            <div class="shake mb-5 flex items-start gap-3 bg-red-500/15 border border-red-400/30 rounded-2xl px-4 py-3.5">
                <i class='bx bxs-error-circle text-red-400 text-xl flex-shrink-0 mt-0.5'></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-red-300 text-sm font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <!-- Username / NIP / Email -->
                <div class="mb-4">
                    <label class="block text-blue-100/80 text-xs font-semibold uppercase tracking-widest mb-2">
                        Username / NIP / Email
                    </label>
                    <div class="relative">
                        <i class='bx bxs-user absolute left-4 top-1/2 -translate-y-1/2 text-blue-300/60 text-lg'></i>
                        <input
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            placeholder="Masukkan username atau NIP"
                            autocomplete="username"
                            class="input-field w-full pl-11 pr-4 py-3.5 rounded-xl text-sm font-medium"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-blue-100/80 text-xs font-semibold uppercase tracking-widest mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <i class='bx bxs-lock-alt absolute left-4 top-1/2 -translate-y-1/2 text-blue-300/60 text-lg'></i>
                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            class="input-field w-full pl-11 pr-12 py-3.5 rounded-xl text-sm font-medium"
                            required
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="eye-btn absolute right-4 top-1/2 -translate-y-1/2 text-blue-300/40 focus:outline-none"
                        >
                            <i class='bx bx-show text-xl' id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    id="submitBtn"
                    class="btn-submit w-full py-3.5 rounded-xl text-white font-bold text-sm tracking-wide flex items-center justify-center gap-2"
                >
                    <span id="btnText">Masuk ke Panel Admin</span>
                    <i class='bx bx-log-in-circle text-xl' id="btnIcon"></i>
                    <div class="spinner hidden" id="btnSpinner"></div>
                </button>

            </form>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-blue-300/40 text-xs">
                    Sistem Absensi Digital &mdash; SMKN 8 Medan &copy; {{ date('Y') }}
                </p>
            </div>

        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bx bx-hide text-xl';
            } else {
                input.type = 'password';
                icon.className = 'bx bx-show text-xl';
            }
        }

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const icon = document.getElementById('btnIcon');
            const spinner = document.getElementById('btnSpinner');

            btn.disabled = true;
            btn.style.opacity = '0.85';
            text.textContent = 'Memverifikasi...';
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
        });
    </script>

</body>
</html>
