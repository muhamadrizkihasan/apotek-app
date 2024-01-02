@extends('layouts.template')

@section('content')
    <div class="container mt-3">
        <form action="{{ route('order.store') }}" class="m-auto p-5" method="POST">
            @csrf
            {{-- Validasi error message --}}
            @if ($errors->any())
                <ul class="alert alert-danger p-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <p>Penanggung Jawab : <b>{{ Auth::user()->name }}</b></p>
            <div class="mb-3 row">
                <label for="name_customer" class="col-sm-2 col-form-label" style="width: 12%">Nama Pembeli :</label>
                <div class="col-sm-10" style="width: 73.5%">
                    <input type="text" class="form-control" name="name_customer">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="medicines" class="col-sm-2 col-form-label" style="width: 12%">Obat :</label>
                <div class="col-sm-10">
                    {{-- Name dibuat array karena nantinya data obat (medicines) akan berbentuk array/data bisa lebih dari satu --}}
                    <select name="medicines[]" id="medicines" class="form-select mb-2" style="width: 88%">
                        <option selected hidden disabled>Pesanan 1</option>
                        @foreach ($medicines as $medicine)
                            <option value="{{ $medicine['id'] }}">{{ $medicine['name'] }}</option>
                        @endforeach
                    </select>
                    {{-- div pembungkus untuk tambahan select yang akan muncul --}}
                    <div id="wrap-select"></div>
                    <br>
                    <p style="cursor: pointer" class="text-primary mb-4" onclick="addSelect()">+ Tambah Pesanan</p>
                </div>
                <button type="submit" class="btn btn-block btn-lg btn-primary">Konfirmasi Pembelian</button>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script>
        // Definisikan no sebagai 2
        let no = 2;
        function addSelect() {
            let el = `<select name="medicines[]" id="medicines" class="form-select mb-3" style="width: 88%">
                        <option selected hidden disabled>Pesanan ${no}</option>
                        @foreach ($medicines as $medicine)
                            <option value="{{ $medicine['id'] }}">{{ $medicine['name'] }}</option>
                        @endforeach
                    </select>`;

            // Gunakan jquery untuk memanggil HTML tempat el baru akan ditambahkan
            // append : Menambahkan element HTML dibagian bawah sebelum penutup tag terkait
            $("#wrap-select").append(el);
            // Agar no pesanan bertambah sesuai jumlah select
            no++;
        }
    </script>
@endpush