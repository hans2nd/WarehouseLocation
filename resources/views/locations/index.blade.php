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
            // Update counter setelah toggle
            if (typeof updateSelectionCount === 'function') updateSelectionCount();
        }
    }">

        {{-- Form Print Batch & Bulk Delete (Hidden) --}}
        <form id="print-form" action="{{ route('locations.print_batch') }}" method="POST" target="_blank" class="hidden">
            @csrf
            <input type="hidden" name="print_type" id="print-type-input" value="standard">
            <input type="hidden" name="arrow_direction" id="arrow-direction-input" value="alternate">
        </form>

        {{-- Form Truncate (Hidden) --}}
        <form id="truncate-form" action="{{ route('locations.truncate') }}" method="POST" class="hidden">
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

                    {{-- TOMBOL KOSONGKAN SEMUA --}}
                    <button type="button" onclick="confirmTruncate()"
                        class="inline-flex items-center justify-center bg-red-800 text-gray-700 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        Kosongkan Semua
                    </button>

                    {{-- TOMBOL PRINT BATCH --}}
                    <button type="button" onclick="showPrintOptions()"
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
                <form id="import-form" action="{{ route('locations.import') }}" method="POST" enctype="multipart/form-data"
                    class="flex gap-2 w-full sm:w-auto" onsubmit="showImportLoading()">
                    @csrf
                    <div class="flex rounded-md shadow-sm w-full">
                        <input type="file" name="file" id="import-file"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-l-lg"
                            required>
                        <button type="submit" id="import-btn"
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

        {{-- Info Bar: Jumlah Lokasi Terpilih --}}
        <div class="px-6 py-3 bg-blue-50 border-b border-blue-100 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-4 text-sm">
                {{-- Total Data --}}
                <div class="flex items-center gap-2 text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span>Total: <strong class="text-gray-800">{{ $locations->total() }}</strong> lokasi</span>
                </div>
                
                {{-- Separator --}}
                <span class="text-gray-300">|</span>
                
                {{-- Terpilih (Dynamic dengan JS) --}}
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-blue-700">
                        Terpilih: <strong id="selected-count">0</strong> dari <strong>{{ $locations->count() }}</strong> di halaman ini
                    </span>
                </div>
            </div>
            
            {{-- Quick Actions saat ada yang dipilih --}}
            <div id="selection-actions" class="hidden flex items-center gap-2">
                <span class="text-xs text-gray-500 mr-2">Aksi cepat:</span>
                <button type="button" onclick="clearSelection()" 
                    class="text-xs bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 px-2 py-1 rounded transition">
                    Batalkan Pilihan
                </button>
            </div>
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
        // Update jumlah lokasi yang dipilih
        function updateSelectionCount() {
            const checkboxes = document.querySelectorAll('.loc-checkbox:checked');
            const count = checkboxes.length;
            const countElement = document.getElementById('selected-count');
            const actionsElement = document.getElementById('selection-actions');
            
            if (countElement) {
                countElement.textContent = count;
                
                // Highlight jika ada yang dipilih
                if (count > 0) {
                    countElement.classList.add('text-blue-600', 'bg-blue-100', 'px-2', 'py-0.5', 'rounded');
                    actionsElement.classList.remove('hidden');
                } else {
                    countElement.classList.remove('text-blue-600', 'bg-blue-100', 'px-2', 'py-0.5', 'rounded');
                    actionsElement.classList.add('hidden');
                }
            }
        }
        
        // Batalkan semua pilihan
        function clearSelection() {
            document.querySelectorAll('.loc-checkbox').forEach(cb => cb.checked = false);
            const selectAllCheckbox = document.querySelector('[x-data] input[type="checkbox"]');
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateSelectionCount();
        }
        
        // Event listener untuk checkbox
        document.addEventListener('DOMContentLoaded', function() {
            // Update count saat checkbox individual berubah
            document.querySelectorAll('.loc-checkbox').forEach(cb => {
                cb.addEventListener('change', updateSelectionCount);
            });
            
            // Observer untuk checkbox Select All (Alpine.js)
            const observer = new MutationObserver(updateSelectionCount);
            document.querySelectorAll('.loc-checkbox').forEach(cb => {
                observer.observe(cb, { attributes: true, attributeFilter: ['checked'] });
            });
            
            // Initial count
            updateSelectionCount();
        });

        // Tampilkan pilihan tipe print
        function showPrintOptions() {
            let checkboxes = document.querySelectorAll('.loc-checkbox:checked');
            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih data dulu',
                    text: 'Silakan centang lokasi yang ingin dicetak.'
                });
                return;
            }
            
            Swal.fire({
                title: '🖨️ Pilih Tipe Cetak',
                html: `
                    <p class="text-gray-600 mb-4">Pilih layout cetak untuk <strong>${checkboxes.length}</strong> lokasi:</p>
                    <div style="display: flex; flex-direction: column; gap: 12px; text-align: left;">
                        <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.2s;" 
                               onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#e5e7eb'">
                            <input type="radio" name="swal-print-type" value="standard" checked style="margin-right: 12px; width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f2937;">📋 Standard</strong>
                                <p style="font-size: 12px; color: #6b7280; margin: 0;">QR Code standar dengan kode lokasi</p>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.2s;"
                               onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#e5e7eb'">
                            <input type="radio" name="swal-print-type" value="flooring" style="margin-right: 12px; width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f2937;">🏭 Flooring</strong>
                                <p style="font-size: 12px; color: #6b7280; margin: 0;">Layout grid untuk lantai</p>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.2s;"
                               onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#e5e7eb'">
                            <input type="radio" name="swal-print-type" value="racking" style="margin-right: 12px; width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f2937;">📦 Racking</strong>
                                <p style="font-size: 12px; color: #6b7280; margin: 0;">Layout grid dengan panah arah untuk rak</p>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.2s;"
                               onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#e5e7eb'">
                            <input type="radio" name="swal-print-type" value="double_deep" style="margin-right: 12px; width: 18px; height: 18px;">
                            <div>
                                <strong style="color: #1f2937;">📚 Double Deep</strong>
                                <p style="font-size: 12px; color: #6b7280; margin: 0;">Layout grid dengan panah untuk double deep</p>
                            </div>
                        </label>
                    </div>
                    
                    {{-- Opsi Arah Panah --}}
                    <div id="arrow-options" style="margin-top: 16px; padding: 12px; background: #f3f4f6; border-radius: 8px; display: none;">
                        <p style="font-weight: 600; color: #374151; margin-bottom: 8px;">↔ Arah Panah:</p>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; padding: 8px 12px; background: white; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer;">
                                <input type="radio" name="swal-arrow-dir" value="alternate" checked style="margin-right: 8px;">
                                <span style="font-size: 13px;">↔ Bergantian</span>
                            </label>
                            <label style="display: flex; align-items: center; padding: 8px 12px; background: white; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer;">
                                <input type="radio" name="swal-arrow-dir" value="left" style="margin-right: 8px;">
                                <span style="font-size: 13px;">← Semua Kiri</span>
                            </label>
                            <label style="display: flex; align-items: center; padding: 8px 12px; background: white; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer;">
                                <input type="radio" name="swal-arrow-dir" value="right" style="margin-right: 8px;">
                                <span style="font-size: 13px;">→ Semua Kanan</span>
                            </label>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Cetak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#1f2937',
                didOpen: () => {
                    // Show/hide arrow options based on print type selection
                    const radios = document.querySelectorAll('input[name="swal-print-type"]');
                    const arrowOptions = document.getElementById('arrow-options');
                    radios.forEach(radio => {
                        radio.addEventListener('change', () => {
                            if (['racking', 'flooring', 'double_deep'].includes(radio.value)) {
                                arrowOptions.style.display = 'block';
                            } else {
                                arrowOptions.style.display = 'none';
                            }
                        });
                    });
                },
                preConfirm: () => {
                    const selectedType = document.querySelector('input[name="swal-print-type"]:checked');
                    const selectedArrow = document.querySelector('input[name="swal-arrow-dir"]:checked');
                    return {
                        type: selectedType ? selectedType.value : 'standard',
                        arrow: selectedArrow ? selectedArrow.value : 'alternate'
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('print-type-input').value = result.value.type;
                    document.getElementById('arrow-direction-input').value = result.value.arrow;
                    
                    let form = document.getElementById('print-form');
                    form.action = "{{ route('locations.print_batch') }}";
                    form.setAttribute('target', '_blank');
                    form.submit();
                }
            });
        }

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

        function confirmTruncate() {
            Swal.fire({
                title: '⚠️ Kosongkan Semua Data?',
                html: `
                    <p class="text-gray-600 mb-4">Aksi ini akan menghapus <strong>SEMUA</strong> data lokasi gudang secara permanen.</p>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-top: 12px;">
                        <p style="color: #991b1b; font-size: 14px; font-weight: 600;">🚨 Peringatan!</p>
                        <p style="color: #7f1d1d; font-size: 13px;">Data yang sudah dihapus tidak dapat dikembalikan.</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kosongkan Semua!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Konfirmasi kedua untuk keamanan
                    Swal.fire({
                        title: 'Konfirmasi Terakhir',
                        text: 'Ketik "HAPUS" untuk mengkonfirmasi penghapusan semua data:',
                        input: 'text',
                        inputPlaceholder: 'Ketik HAPUS',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Hapus Permanen',
                        cancelButtonText: 'Batal',
                        inputValidator: (value) => {
                            if (value !== 'HAPUS') {
                                return 'Ketik "HAPUS" dengan benar untuk melanjutkan';
                            }
                        }
                    }).then((result2) => {
                        if (result2.isConfirmed) {
                            document.getElementById('truncate-form').submit();
                        }
                    });
                }
            });
        }

        // Loading Import dengan SweetAlert Progress
        function showImportLoading() {
            const fileInput = document.getElementById('import-file');
            if (!fileInput.files.length) {
                return false;
            }
            
            const importBtn = document.getElementById('import-btn');
            importBtn.disabled = true;
            
            // Tampilkan SweetAlert dengan progress bar
            Swal.fire({
                title: 'Mengimport Data...',
                html: `
                    <p class="mb-4">Mohon tunggu, proses import sedang berjalan.</p>
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2" style="background: #e5e7eb; border-radius: 9999px; height: 12px; overflow: hidden;">
                        <div id="swal-progress-bar" style="height: 100%; width: 0%; background: linear-gradient(to right, #3b82f6, #2563eb); border-radius: 9999px; transition: width 0.3s ease-out;"></div>
                    </div>
                    <p id="swal-progress-text" class="text-sm text-gray-600">Memproses file...</p>
                    <div style="margin-top: 16px; padding: 12px; background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px;">
                        <p style="font-size: 12px; color: #b45309;">⚠️ Jangan tutup atau refresh halaman ini!</p>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    // Animasi progress bar
                    let progress = 0;
                    const messages = [
                        'Membaca file Excel...',
                        'Memvalidasi data...',
                        'Menyimpan ke database...',
                        'Hampir selesai...'
                    ];
                    
                    const progressBar = document.getElementById('swal-progress-bar');
                    const progressText = document.getElementById('swal-progress-text');
                    
                    window.importProgressInterval = setInterval(() => {
                        if (progress < 90) {
                            progress += Math.random() * 15;
                            if (progress > 90) progress = 90;
                            if (progressBar) progressBar.style.width = progress + '%';
                            
                            // Update pesan
                            const msgIndex = Math.min(Math.floor(progress / 25), messages.length - 1);
                            if (progressText) progressText.textContent = messages[msgIndex];
                        }
                    }, 500);
                }
            });
            
            return true; // Lanjutkan form submission
        }
        
        // Auto-close SweetAlert jika halaman reload (import selesai)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                Swal.close();
                if (window.importProgressInterval) {
                    clearInterval(window.importProgressInterval);
                }
            }
        });
    </script>
@endsection
