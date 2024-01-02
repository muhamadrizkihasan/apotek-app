<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Exports\OrderExports;
use App\Models\Order;
use Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil seluruh data pada table orders dengan pagination per halaman 10 data serta mengambil data relasi function bernama user pada model Order
        // with = Mengambil function relasi PK ke FK / FK ke PK dari model
        // Isi di petik disamakan dengan nama function di modelnya
        $orders = Order::with('user')->simplePaginate(5);
        return view("order.kasir.index", compact('orders'));
    }

    public function data()
    {
        $orders = Order::with('user')->simplePaginate(5);
        return view("order.admin.index", compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::all();
        return view("order.kasir.create", compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_customer' => 'required',
            'medicines' => 'required',
        ]);
        

        // array_count_values : Menghitung jumlah item sama di dalam array
        // Hasilnya berbentuk : "itemnya" => "Jumlah yang sama"
        // Menentukan qty
        // [ "item" => "jumlah" ]
        $medicines = array_count_values($request->medicines);

        // Menampung detail berbentuk array-array assoc dari obat-obat yang dipilih
        $dataMedicines = [];
        foreach ($medicines as $key => $value) {
            // Mencari data obat berdasarkan id (obat yang dipilih)
            $medicine = Medicine::where('id', $key)->first();
            $arrayAssoc = [
                "id" => $key,
                "name_medicine" => $medicine['name'],
                "price" => $medicine['price'],
                "qty" => $value,
                // (int) => memastikan dan mengubah tipe data menjadi integer 
                "price_after_qty" => (int)$value * (int)$medicine['price'],
            ];
            // Format assoc dimasukan ke array penampung sebelumnya
            array_push($dataMedicines, $arrayAssoc);
        }

        // Variable totalPrice awalnya 0
        $totalPrice = 0;

        // Looping data dari array penampung yang ada di format
        foreach ($dataMedicines as $formatArray) {
            // Dia bakal menjumlahkan total price sebelumnya ditambah data harga dari price_after_qty
            $totalPrice += (int)$formatArray['price_after_qty'];
        }

        $prosesTambahData = Order::create([
            'name_customer' => $request->name_customer,
            'medicines' => $dataMedicines,
            'total_price' => $totalPrice,
            // user_id menyimpan data id dari orang yang login kasir penanggung jawab
            'user_id' => Auth::user()->id,
        ]);

        // redirect ke halaman struk
        return redirect()->route('order.struk', $prosesTambahData['id']);
    }

    public function strukPembelian($id)
    {
        $order = Order::where('id', $id)->first();
        return view('order.kasir.struk', compact('order'));
    }

    public function downloadPDF ($id)
    {
        // get data yang akan ditampilkan di pdf
        // data yang dikirm ke pdf wajib bertipe array
        // $order = Order::where('id', $id)->first()->toArray();
        $order = Order::find($id)->toArray();

        // Ketika data dipanggil di blade pdf, akan dipanggil dengan $ apa
        view()->share('order', $order);

        // Lokasi dan nama blade yang akan di download ke pdf serta data yang akan dikembalikan
        $pdf = PDF::loadview('order.kasir.download-pdf', $order);

        // Ketika di download nama file nya apa
        return $pdf->download('Bukti Pembelian.pdf');
    }

    public function search(Request $request)
    {
        $searchData = $request->input('search');
        $orders = Order::whereDate('created_at', $searchData)->simplepaginate(5);

        return view('order.kasir.index', compact('orders'));
    }
    
    public function searchAdmin(Request $request)
    {
        $searchData = $request->input('search');
        $orders = Order::whereDate('created_at', $searchData)->simplepaginate(5);

        return view('order.admin.index', compact('orders'));
    }

    public function downloadExcel()
    {
        $file_name = 'Data seluruh pembelian.xlsx';
        return Excel::download(new OrderExports, $file_name);
    }

        /**}
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
