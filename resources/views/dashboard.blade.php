@extends('layouts.template')

@section('content')
    <div class="jumbotron p-4 bg-light mt-5">
        <div class="container">
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif
            @if (Session::get('gagal'))
                <div class="alert alert-danger">{{ Session::get('gagal') }}</div>
            @endif
            <h1 class="display-4">Apotek App</h1>
            {{-- Mengambil data dari table users sesuai data login --}}
            <h2 class="display-4">Selamat Datang {{ Auth::user()->name }}!</h2>
            <hr class="my-4" />
            <p>Aplikasi ini digunakan hanya oleh pegawai administrator APOTEK. Digunakan untuk mengelola data obat, penyetokan, juga pembelian {kasir}</p>
        </div>
    </div>
@endsection