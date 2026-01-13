@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">

            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Edit Lokasi: <span
                        class="text-blue-600">{{ $location->location_code }}</span></h2>
                <a href="{{ route('locations.index') }}" class="text-sm text-blue-600 hover:underline">Kembali ke List</a>
            </div>

            <form action="{{ route('locations.update', $location->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT') {{-- PENTING UNTUK UPDATE --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Location ID --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location ID *</label>
                        <input type="text" name="location_code"
                            value="{{ old('location_code', $location->location_code) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2"
                            required>
                        @error('location_code')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Branch --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Branch *</label>
                        <input type="text" name="branch" value="{{ old('branch', $location->branch) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2"
                            required>
                    </div>

                    {{-- Warehouse --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Warehouse *</label>
                        <input type="text" name="warehouse" value="{{ old('warehouse', $location->warehouse) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2"
                            placeholder="Contoh: Gudang A" required>
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description *</label>
                        <input type="text" name="description" value="{{ old('description', $location->description) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2"
                            required>
                    </div>

                    {{-- Pick Priority --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pick Priority *</label>
                        <input type="number" name="pick_priority"
                            value="{{ old('pick_priority', $location->pick_priority) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2"
                            required>
                    </div>

                    {{-- Path --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Path *</label>
                        <input type="text" name="path" value="{{ old('path', $location->path) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2"
                            required>
                    </div>

                    {{-- Active Status --}}
                    <div class="flex items-center mt-6">
                        <input id="is_active" name="is_active" type="checkbox" {{ $location->is_active ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Status Aktif
                        </label>
                    </div>

                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <x-button.secondary :href="route('locations.index')">Batal</x-button.secondary>
                    <x-button.primary type="submit">Update Data</x-button.primary>
                </div>
            </form>
        </div>
    </div>
@endsection
