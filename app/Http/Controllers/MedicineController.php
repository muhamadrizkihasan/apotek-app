<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Medicine::where('name', 'vitamin')->get();
        // Manggil HTML yang ada di folder resource/views/medicine/index.blade.php
        $medicines = Medicine::orderBy('name', 'ASC')->simplePaginate(5);
        //manggil html
        return view('medicine.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan layouting HTML pada folder resource-views
        return view('medicine.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        // request $request untuk mengambil data yang diinput si user
        //validasi
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);
    

        Medicine::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);
        // Atau jika seluruh data input akan dimasukkan langsung ke db bisa dengan perintah Medicine::create($request->all());

        return redirect()->back()->with('success', 'Berhasil menambahkan Data Obat!');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $medicine = Medicine::find($id);
        // Mengembalikan bentuk json dikirim data yang diambil dengan response status code 200
        // Response status code api :
        // 200 -> success/ok
        // 400 an -> error kode / validasi input user
        // 419 -> error token csrf
        // 500 an -> error server hosting
        return response()->json($medicine, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $medicine = Medicine::find($id);

        return view('medicine.edit', compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //validasi
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
        ]);

        Medicine::where('id', $id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
        ]);
        return redirect()->route('medicine.data')->with('success', 'Berhasil Mengubah Data Obat!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cari dan hapus data
        Medicine::where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Berhasil menghapus data!');
    }

    public function stock()
    {
        $medicines = Medicine::orderBy('stock', 'ASC')->simplePaginate(5);

        // Untuk mengambil variable
        return view('medicine.stock', compact('medicines'));
    }

    public function stockEdit($id) 
    {
        $medicine = Medicine::find($id);

        return response()->json($medicine);
    }

    public function updateStock(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'stock' => 'required|numeric',
        ]);

        $medicine = Medicine::find($id);

        if ($request->stock <= $medicine['stock']) {
            return response()->json(["message" => "Stock yang diinput tidak boleh kurang dari stock sebelumnya"], 400);
        } else {
            $medicine->update(["stock" => $request->stock]);
            return response()->json("Berhasil", 200);
        }
    }
}