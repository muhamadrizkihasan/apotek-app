@extends('layouts.template')

@section('content')
    <form action="{{ route('auth-login') }}" class="card p-4 mt-5" method="POST">
        @csrf
        {{-- Menampilkan error validasi --}}
        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        {{-- Menampilkan message dari controller with namanya failed --}}
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif

        @if (Session::get('logout'))
            <div class="alert alert-primary">{{ Session::get('logout') }}</div>
        @endif
        <div class="mb-3 mx-1">
            <label for="email" class="form-label col-2">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Masukan email anda...">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3 mx-1">
            <label for="password" class="form-label col-3">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Masukan password anda...">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
    </form>
@endsection