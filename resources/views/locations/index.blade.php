@extends('layouts.app')

@section('content')
    {{-- Wrapper Alpine.js untuk handle Logic Select All --}}
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden" x-data="{
        selectAll: false,
        toggleAll() {
            this.selectAll = !this.selectAll;
            document.querySelectorAll('.loc-checkbox').forEach((el) => {
                el.checked = this.selectAll;
            });
        }
    }">

        {{-- Form Print Batch & Bulk Delete (Hidden) --}}
        <form id="print-form" action="{{ route('locations.print_batch') }}" method="POST" target="_blank" class="hidden">
            @csrf
        </form>

        {{-- Header Halaman --}}
        <div
            class="p-6 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Master Lokasi Gudang</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data lokasi penyimpanan, warehouse, dan branch.</p>
            </div>

            {{-- Bagian Tombol Aksi (Kanan) --}}
            <div class="flex flex-col sm:items-end gap-2 w-full sm:w-auto">
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    {{-- TOMBOL TAMBAH DATA --}}
                    <a href="{{ route('locations.create') }}"
                        class="inline-flex items-center justify-center bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Baru
                    </a>

                    {{-- TOMBOL BULK DELETE --}}
                    <button type="button" onclick="confirmBulkDelete()"
                        class="inline-flex items-center justify-center bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Hapus Terpilih
                    </button>

                    {{-- TOMBOL PRINT BATCH --}}
                    <button type="submit" form="print-form" onclick="setFormToPrint()"
                        class="inline-flex items-center justify-center bg-gray-800 text-white hover:bg-black px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Cetak QR
                    </button>

                    {{-- Tombol Export --}}
                    <a href="{{ route('locations.export') }}"
                        class="inline-flex items-center justify-center bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export
                    </a>
                </div>

                {{-- Form Import (Baris Terpisah) --}}
                <form action="{{ route('locations.import') }}" method="POST" enctype="multipart/form-data"
                    class="flex gap-2 w-full sm:w-auto">
                    @csrf
                    <div class="flex rounded-md shadow-sm w-full">
                        <input type="file" name="file"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-l-lg"
                            required>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg text-sm font-medium transition -ml-px whitespace-nowrap">
                            Import
                        </button>
                    </div>
                </form>

                {{-- Link Download Template --}}
                <div class="text-xs text-gray-500">
                    Butuh format excel?
                    <a href="{{ route('locations.template') }}" class="text-blue-600 hover:underline font-medium">
                        Unduh Template Disini
                    </a>
                </div>
            </div>
        </div>

        {{-- Form Pencarian --}}
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form action="{{ route('locations.index') }}" method="GET">
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-0">
                    {{-- Input Group dengan Tombol --}}
                    <div class="flex flex-1 rounded-lg shadow-sm">
                        {{-- Ikon Search --}}
                        <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        {{-- Input Field --}}
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="flex-1 min-w-0 block w-full px-3 py-2.5 border border-gray-300 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ request('search') ? '' : 'rounded-r-lg sm:rounded-r-none' }}"
                            placeholder="Cari lokasi, warehouse, atau deskripsi...">
                        {{-- Tombol Cari (Desktop) --}}
                        <button type="submit"
                            class="hidden sm:inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition {{ request('search') ? '' : 'rounded-r-lg' }}">
                            Cari
                        </button>
                        {{-- Tombol Reset (jika ada pencarian) --}}
                        @if(request('search'))
                        <a href="{{ route('locations.index') }}"
                            class="hidden sm:inline-flex items-center px-4 py-2.5 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-r-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                        @endif
                    </div>
                    {{-- Tombol Cari & Reset (Mobile) --}}
                    <div class="flex sm:hidden gap-2">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari
                        </button>
                        @if(request('search'))
                        <a href="{{ route('locations.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition shadow-sm">
                            Reset
                        </a>
                        @endif
                    </div>
                </div>
                {{-- Info hasil pencarian --}}
                @if(request('search'))
                <div class="mt-2 text-sm text-gray-600">
                    Menampilkan hasil untuk: <span class="font-medium text-blue-600">"{{ request('search') }}"</span>
                </div>
                @endif
            </form>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div
                class="m-6 p-4 bg-green-50 text-green-700 border-l-4 border-green-500 rounded-r shadow-sm flex items-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" @click="toggleAll()"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 font-semibold">Location ID</th>
                        <th class="px-6 py-4 font-semibold">Branch</th>
                        <th class="px-6 py-4 font-semibold">Warehouse</th>
                        <th class="px-6 py-4 font-semibold">Description</th>
                        <th class="px-6 py-4 font-semibold">Pick Priority</th>
                        <th class="px-6 py-4 font-semibold">Path</th>
                        <th class="px-6 py-4 font-semibold text-center">Active</th>
                        <th class="px-6 py-4 font-semibold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($locations as $loc)
                        <tr class="bg-white hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="ids[]" value="{{ $loc->id }}" form="print-form"
                                    class="loc-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <span
                                    class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded border border-blue-200 font-mono">
                                    {{ $loc->location_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $loc->branch }}</td>
                            <td class="px-6 py-4">{{ $loc->warehouse ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $loc->description }}</td>
                            <td class="px-6 py-4">{{ $loc->pick_priority }}</td>
                            <td class="px-6 py-4">{{ $loc->path }}</td>
                            <td class="px-6 py-4 text-center">
                                @if ($loc->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('locations.edit', $loc->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                    <button type="button" onclick="confirmDelete({{ $loc->id }})"
                                        class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                </div>
                                <form id="delete-form-{{ $loc->id }}"
                                    action="{{ route('locations.destroy', $loc->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-gray-500">
                                Belum ada data lokasi. Silakan Import Excel atau Tambah Data Manual.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $locations->links() }}
        </div>
    </div>

    <script>
        function setFormToPrint() {
            let form = document.getElementById('print-form');
            form.action = "{{ route('locations.print_batch') }}";
            form.setAttribute('target', '_blank');
        }

        function confirmBulkDelete() {
            let checkboxes = document.querySelectorAll('.loc-checkbox:checked');
            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih data dulu',
                    text: 'Silakan centang lokasi yang ingin dihapus.'
                });
                return;
            }
            Swal.fire({
                title: 'Hapus ' + checkboxes.length + ' Lokasi?',
                text: "Data yang dipilih akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('print-form');
                    form.action = "{{ route('locations.bulk_delete') }}";
                    form.removeAttribute('target');
                    form.submit();
                }
            })
        }
    </script>
@endsection
