@extends('layouts.template')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-6">
                <form action="{{ route('order.search') }}" method="GET">
                    @csrf
                    <div class="input-group">
                        <input type="date" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username with two button addons" name="search">
                        <button class="btn btn-info" type="submit">Cari Data</button>
                        <a href="{{ route('order.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
            <div class="col-3"></div>
            <div class="col-3">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('order.create') }}" class="btn btn-primary">Tambah Pembelian</a>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-striped w-100 table-bordered table-hover mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pembeli</th>
                <th>Pesanan</th>
                <th>Total Bayar</th>
                <th>Kasir</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    {{-- currentPage : ambil posisi ada di page keberapa -> 1. (misal ada klik next lagi ada di page 2 berarti id 2 - 1 = 1) perPage : Mengambil jumlah data yang ditampilkan perPagenya berapa (ada di controller bagian paginate/simplepaginate, misal 5), loop -> index : mengambil index or array (mulai dari 0) + 1 --}}
                    {{-- Jadi (2 - 1) = 5 + 1 = 6 (dimulai dari angka di page ke-2nya) --}}
                    <td>{{ ($orders->currentPage() - 1) * $orders->perpage() + $loop->index + 1 }}</td>
                    <td>{{ $order['name_customer'] }}</td>
                    {{-- neested loop : looping didalam looping --}}
                    {{-- Karena column medicines pada table orders tipe datanya json, id untuk akses nya perlu looping --}}
                    <td>
                        <ol>
                            @foreach ($order['medicines'] as $medicine)
                                {{-- Tampilkan yang ingin ditampilkan --}}
                                {{-- 1. Nama obat Rp. 1000 (qty 2) = Rp. 2000 --}}
                                <li>{{ $medicine['name_medicine'] }} <small>Rp. {{ number_format($medicine['price'], 0, '.', ',') }}<b> (qty : {{ $medicine['qty'] }})</b></small> = Rp. {{ number_format($medicine['price_after_qty'], 0, '.', ',') }}</li>
                            @endforeach
                        </ol>
                    </td>
                    @php 
                        $ppn = $order['total_price'] * 0.1;
                    @endphp
                    <td>Rp. {{ number_format(($order['total_price'] + $ppn), 0, '.', ',') }}</td>
                    <td>{{ $order['user']['name'] }}
                        <a href="mailto:{{ $order['user']['email'] }}">
                            ({{ $order['user']['email'] }})
                        </a>
                    </td>
                    @php
                        // Set lokasi waktu berdasarkan penamaan dan jam WIB Indonesia
                        setlocale(LC_ALL, 'IND');  
                    @endphp
                    {{-- Carbon --}}
                    <td>{{ Carbon\Carbon::parse($order['created_at'])->formatLocalized('%d %B %Y') }}</td>
                    <td>
                        <a href="{{ route('order.download-pdf', $order['id']) }}" class="btn btn-secondary">Download Setruk</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
            @if ($orders->count())
                    {{ $orders->links() }}
            @endif
        </div>
@endsection
