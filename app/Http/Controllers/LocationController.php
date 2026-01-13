<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\LocationsExport;
use App\Imports\LocationsImport;
use App\Models\WarehouseLocation;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LocationTemplateExport;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = WarehouseLocation::query();

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('location_code', 'like', "%{$search}%")
                  ->orWhere('warehouse', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $locations = $query->latest()->paginate(100)->withQueryString();
        return view('locations.index', compact('locations'));
    }

    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new LocationsImport, $request->file('file'));
        
        return back()->with('success', 'Data berhasil diimport!');
    }

    public function export() 
    {
        return Excel::download(new LocationsExport, 'warehouse_locations.xlsx');
    }

    public function downloadTemplate()
    {
        return Excel::download(new LocationTemplateExport, 'template_lokasi_gudang.xlsx');
    }


    public function printBatch(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:warehouse_locations,id',
            'print_type' => 'nullable|string|in:standard,flooring,racking,double_deep',
            'arrow_direction' => 'nullable|string|in:alternate,left,right',
        ]);

        // Ambil data berdasarkan ID yang dicentang
        $locations = WarehouseLocation::whereIn('id', $request->ids)->get();
        
        // Tentukan view berdasarkan tipe print
        $printType = $request->input('print_type', 'standard');
        $arrowDirection = $request->input('arrow_direction', 'alternate');
        
        $viewMap = [
            'standard' => 'locations.print',
            'flooring' => 'locations.print-flooring',
            'racking' => 'locations.print-racking',
            'double_deep' => 'locations.print-double-deep',
        ];
        
        $viewName = $viewMap[$printType] ?? 'locations.print';

        return view($viewName, compact('locations', 'printType', 'arrowDirection'));
    }

    public function bulkDestroy(Request $request)
    {
        // Validasi apakah ada ID yang dikirim
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:warehouse_locations,id', // Pastikan ID valid
        ]);

        // Hapus data
        $count = \App\Models\WarehouseLocation::whereIn('id', $request->ids)->delete();

        return back()->with('success', $count . ' Lokasi berhasil dihapus.');
    }

    // Menampilkan Form Tambah
    public function create()
    {
        return view('locations.create'); // Anda perlu buat file view ini
    }

    // Menyimpan Data Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_code' => 'required|unique:warehouse_locations,location_code',
            'branch' => 'required',
            'warehouse' => 'required',
            'description' => 'required',
            'pick_priority' => 'required|integer',
            'path' => 'required',
        ]);

        // Checkbox HTML return "on" or null, kita handle agar jadi boolean
        $validated['is_active'] = $request->has('is_active');

        WarehouseLocation::create($validated);
        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan');
    }

    // Menampilkan Form Edit
    public function edit($id)
    {
        $location = WarehouseLocation::findOrFail($id);
        return view('locations.edit', compact('location')); // Anda perlu buat file view ini
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $location = WarehouseLocation::findOrFail($id);
        
        $validated = $request->validate([
            'location_code' => 'required|unique:warehouse_locations,location_code,'.$id,
            'branch' => 'required',
            'warehouse' => 'required',
            'description' => 'required',
            'pick_priority' => 'required|integer',
            'path' => 'required',
        ]);

        $location->update([
            'location_code' => $request->location_code,
            'branch' => $request->branch,
            'warehouse' => $request->warehouse,
            'description' => $request->description,
            'pick_priority' => $request->pick_priority,
            'path' => $request->path,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diupdate');
    }

    // Hapus Data
    public function destroy($id)
    {
        WarehouseLocation::findOrFail($id)->delete();
        return back()->with('success', 'Lokasi berhasil dihapus');
    }

    // Kosongkan Semua Data
    public function truncate()
    {
        $count = WarehouseLocation::count();
        WarehouseLocation::truncate();
        return back()->with('success', 'Semua data (' . $count . ' lokasi) berhasil dihapus.');
    }
}