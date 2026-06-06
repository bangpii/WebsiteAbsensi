<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi SMKN 8 Medan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>

<body class="font-sans bg-gradient-to-br from-blue-500 to-indigo-700 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-6">

        <div
            class="bg-white rounded-3xl shadow-2xl p-10 max-w-xl w-full text-center"
            data-aos="zoom-in"
        >

            <!-- Icon -->
            <div class="flex justify-center items-center gap-4 mb-6">
                <i class='bx bxs-school text-6xl text-blue-600'></i>
                <i data-feather="book-open" class="w-14 h-14 text-indigo-600"></i>
            </div>

            <!-- Title -->
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Sistem Absensi SMKN 8 Medan
            </h1>

            <!-- Subtitle -->
            <p class="text-gray-500 text-lg mb-8">
                Website absensi modern berbasis Laravel dan Tailwind CSS
            </p>

            <!-- Button Siswa Saja -->
            <div class="flex justify-center">
                <a
                    href="{{ route('siswa.dashboard') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition duration-300 text-lg"
                >
                    Masuk sebagai Siswa
                </a>
            </div>

        </div>

    </div>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init();</script>

</body>
</html>
