<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Gedung;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KamarController extends Controller
{

    public function index(Request $request)
    {
        $gedungId = $request->input('gedung_id');

        // Ambil semua gedung
        $gedung = Gedung::all();

        // Filter berdasarkan nama gedung jika nama gedung dipilih
        $kamar = Kamar::when($gedungId, function ($query, $gedungId) {
            return $query->whereHas('gedung', function ($query) use ($gedungId) {
                $query->where('id', $gedungId);
            });
        })
            ->select('kamar.id as kamar_id', 'kamar.nomor_kamar', 'kamar.kapasitas', 'kamar.status', 'gedung.nama_gedung')
            ->leftJoin('gedung', 'gedung.id', '=', 'kamar.gedung_id')
            ->orderBy('nomor_kamar', 'asc')
            ->filter(request(['search']))
            ->paginate(10)
            ->withQueryString();

        return view('admin.kamar.kelolaKamar', compact('gedung', 'kamar'));
    }

    public function create()
    {
        $kamar = Kamar::all();
        $gedung = Gedung::all();
        return view('admin.kamar.tambahKamar', compact('kamar', 'gedung'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request data: ', $request->all());

        $existingKamar = Kamar::where('nomor_kamar', $request->nomor_kamar)
            ->where('gedung_id', $request->gedung_id)
            ->first();

        if ($existingKamar) {
            return back()->with('error', 'Kamar dengan nomor tersebut di gedung tersebut sudah tersedia.');
        }

        $validatedData = $request->validate([
            'nomor_kamar' => 'required|max:5',
            'status' => 'required',
            'kapasitas' => 'required|min:1',
            'fasilitas' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'gedung_id' => 'required|uuid',
        ]);

        Log::info('Validated Data: ', $validatedData);

        $kamar = new Kamar();
        $kamar->status = $validatedData['status'];
        $kamar->nomor_kamar = $validatedData['nomor_kamar'];
        $kamar->kapasitas = $validatedData['kapasitas'];
        $kamar->fasilitas = $validatedData['fasilitas'];
        $kamar->deskripsi = $validatedData['deskripsi'];
        $kamar->gedung_id = $validatedData['gedung_id'];

        if ($kamar->save()) {
            Log::info('Kamar saved successfully: ', $kamar->toArray());
        } else {
            Log::error('Failed to save kamar.');
        }

        return redirect()->route('kelola_kamar')->with('success', 'Kamar Berhasil Ditambahkan');
    }

    public function showDetail($id)
    {
        $kamar = Kamar::select('kamar.id as kamar_id', 'kamar.*', 'gedung.*')
            ->leftJoin('gedung', 'kamar.gedung_id', '=', 'gedung.id')
            ->where('kamar.id', $id)
            ->first();

        // Tampilkan formulir donasi
        return view('admin.kamar.detailKamar', compact('kamar'));
    }

    public function edit($id)
    {
        $gedung = Gedung::all();

        $kamar = Kamar::select('kamar.id as kamar_id', 'kamar.*', 'gedung.id as gedung_id', 'gedung.*')
            ->leftJoin('gedung', 'kamar.gedung_id', '=', 'gedung.id')
            ->where('kamar.id', $id)
            ->first();

        // Tampilkan formulir donasi
        return view('admin.kamar.editKamar', compact('gedung', 'kamar'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nomor_kamar' => 'required|max:5',
            'status' => 'required',
            'kapasitas' => 'required|min:1',
            'fasilitas' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'gedung_id' => 'required|uuid',
        ]);

        // Cek apakah ada kamar lain dengan nomor kamar dan gedung yang sama
        $existingKamar = Kamar::where('nomor_kamar', $request->nomor_kamar)
            ->where('gedung_id', $request->gedung_id)
            ->where('id', '!=', $id) // Mengecualikan kamar yang sedang diedit
            ->first();

        if ($existingKamar) {
            return back()->with('error', 'Kamar dengan nomor tersebut di gedung tersebut sudah tersedia.');
        }

        // Find the Kamar record by ID
        $kamar = Kamar::findOrFail($id);

        // Assign values to the kamar instance
        $kamar->status = $validatedData['status'];
        $kamar->nomor_kamar = $validatedData['nomor_kamar'];
        $kamar->kapasitas = $validatedData['kapasitas'];
        $kamar->fasilitas = $validatedData['fasilitas'];
        $kamar->deskripsi = $validatedData['deskripsi'];
        $kamar->gedung_id = $validatedData['gedung_id'];

        try {
            $kamar->save();
            return redirect()->route('kelola_kamar')->with('success', 'Kamar berhasil diubah.');
        } catch (\Exception $e) {
            Log::error('Update failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengubah kamar.');
        }
    }
}
