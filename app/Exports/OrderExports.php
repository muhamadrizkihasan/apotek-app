<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
// Untuk menggunakan function headings
use Maatwebsite\Excel\Concerns\WithHeadings;
// Untuk menggunakan function map
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;


class OrderExports implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // Proses pengambilan data yang akan di export excel
    public function collection()
    {
        return Order::with('user')->get();
    }

    // Menentukan nama-nama column di excelnya
    public function headings() : array
    {
        return [
            "Nama Pembeli", "Pesanan", "Total Harga (+ppn)", "Kasir", "Tanggal"
        ];
    }

    // Data dari collection (Pengembalian dari database) yang akan dimunculan ke excel
    public function map($item) : array
    {
        // Hasil dari column medicines di database yang tadinya array diubah formatnya jadi : 
        // (Vitamin c : qty 2 Rp. 10.000) 
        $pesanan = "";
        foreach ($item['medicines'] as $medicine) {
            $pesanan .= "( " . $medicine['name_medicine'] . " : qty " . $medicine['qty'] . " : Rp. " . number_format($medicine['price_after_qty'], 0, '.', '.') . " ),";
        }

        $totalAfterPPN = $item->total_price + ($item->total_price * 0.1);
        // Urutannya harus sama dengan yang ada di headings
        return [
            $item->name_customer,
            $pesanan,
            "Rp. " . number_format($totalAfterPPN, 0, '.', '.'),
            $item['user']['name'] . "(" . $item['user']['email'] . ")",
            Carbon::parse($item['created_at'])->format("d-m-y H:i:s")
        ];
    }
}
