@extends('layouts.auth')
@section('title','Masuk')
@section('content')
<h2>Senang bertemu lagi.</h2><p>Masuk untuk menemukan sajian favorit dan mengelola pesanan Anda.</p>
@if($errors->any())<div class="notice danger" role="alert">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
<form action="{{ route('login.store') }}" method="post" class="stack">@csrf<label>Email atau username<input class="form-control" name="login" value="{{ old('login') }}" autocomplete="username" required autofocus placeholder="nama@email.com"></label><label>Kata sandi<input class="form-control" type="password" name="password" autocomplete="current-password" required placeholder="Masukkan kata sandi"></label><label class="checkbox"><input type="checkbox" name="remember" value="1"> Ingat saya</label><button class="btn btn-primary">Masuk ke akun →</button></form><p class="switch-auth">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
@endsection
