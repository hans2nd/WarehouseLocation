<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Warehouse System' }}</title>

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js (Opsional: Untuk interaksi dropdown/mobile menu yang halus) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 font-sans antialiased">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0 start-0" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                {{-- Logo & Desktop Menu --}}
                <div class="flex">
                    {{-- Logo --}}
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('locations.index') }}" class="flex items-center gap-2">
                            <div class="bg-blue-600 text-white p-1.5 rounded-lg font-bold">WMS</div>
                            <span class="font-bold text-xl tracking-tight text-gray-800">PL <span
                                    class="text-blue-600">Jakarta</span></span>
                        </a>
                    </div>

                    {{-- Desktop Links --}}
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        {{-- Dashboard Link (Contoh) --}}
                        {{-- <a href="#"
                            class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Dashboard
                        </a> --}}

                        {{-- Master Lokasi Link (Active State Logic) --}}
                        <a href="{{ route('locations.index') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('locations.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                            Master Lokasi
                        </a>

                        {{-- Contoh Menu Lain --}}
                        {{-- <a href="#"
                            class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Laporan
                        </a> --}}
                    </div>
                </div>

                {{-- Right Side (User Profile / Settings) --}}
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">View notifications</span>
                        {{-- Icon Bell --}}
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                    </button>

                    {{-- Profile Dropdown --}}
                    <div class="ml-3 relative" x-data="{ dropdownOpen: false }">
                        <div>
                            <button @click="dropdownOpen = !dropdownOpen" type="button"
                                class="bg-white flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                id="user-menu-button">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name=Admin+Gudang&background=0D8ABC&color=fff"
                                    alt="">
                            </button>
                        </div>

                        {{-- Dropdown Menu Content --}}
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                            style="display: none;">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your
                                Profile</a>
                            <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign
                                out</a>
                        </div>
                    </div>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <span class="sr-only">Open main menu</span>
                        {{-- Icon Menu --}}
                        <svg class="h-6 w-6" x-show="!open" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        {{-- Icon Close --}}
                        <svg class="h-6 w-6" x-show="open" style="display: none;" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div class="sm:hidden" x-show="open" style="display: none;">
            <div class="pt-2 pb-3 space-y-1">
                <a href="#"
                    class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800">Dashboard</a>
                <a href="{{ route('locations.index') }}"
                    class="block pl-3 pr-4 py-2 border-l-4 border-blue-500 text-base font-medium text-blue-700 bg-blue-50">Master
                    Lokasi</a>
                <a href="#"
                    class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800">Laporan</a>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT SLOT --}}
    <main class="pt-20 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>


    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Script Global untuk Menangkap Session Flash --}}
    <script>
        // Cek jika ada session 'success' dari controller
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        // Cek jika ada session 'error'
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
            });
        @endif

        // Fungsi Konfirmasi Hapus (Akan dipanggil dari tombol delete)
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data lokasi ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form delete berdasarkan ID
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</body>

</html>
