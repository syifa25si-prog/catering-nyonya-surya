@extends('layouts.auth')
@section('title','Daftar')
@section('content')
<h2>Mulai momen lezat Anda.</h2><p>Buat akun pelanggan untuk memesan catering dengan mudah.</p>
@if($errors->any())<div class="notice danger" role="alert">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
<form action="{{ route('register.store') }}" method="post" class="stack">@csrf<label>Nama lengkap<input class="form-control" name="name" value="{{ old('name') }}" maxlength="100" required autocomplete="name"></label><label>Email<input class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label><label>Kata sandi<input class="form-control" type="password" name="password" minlength="8" required autocomplete="new-password"><small>Minimal 8 karakter.</small></label><label>Konfirmasi kata sandi<input class="form-control" type="password" name="password_confirmation" required autocomplete="new-password"></label><button class="btn btn-primary">Buat akun pelanggan →</button></form><p class="switch-auth">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
@endsection
