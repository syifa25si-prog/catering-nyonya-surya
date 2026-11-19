@extends('layouts.auth')
@section('title','Masuk')
@section('content')
<div class="auth-heading"><span class="auth-section-label">AKUN ANDA</span><h2>Selamat datang kembali.</h2><p>Masuk untuk memilih sajian favorit dan melanjutkan rencana acara Anda.</p></div>
@if($errors->any())
<div class="notice danger auth-errors" id="login-errors" role="alert"><strong>Belum berhasil masuk.</strong>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
@endif
<form action="{{ route('login.store') }}" method="post" class="stack auth-login-form">
    @csrf
    <div class="auth-field">
        <label for="login">Email atau username</label>
        <div class="auth-input-wrap">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5h16v14H4zM4 6l8 7 8-7" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
            <input id="login" class="form-control" name="login" value="{{ old('login') }}" autocomplete="username" maxlength="255" required autofocus placeholder="Masukkan email atau username" @error('login') aria-invalid="true" aria-describedby="login-errors" @enderror>
        </div>
    </div>
    <div class="auth-field">
        <label for="password">Kata sandi</label>
        <div class="auth-input-wrap">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 10V7a5 5 0 0 1 10 0v3M5 10h14v11H5z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
            <input id="password" class="form-control" type="password" name="password" autocomplete="current-password" required placeholder="Masukkan kata sandi">
            <button class="password-toggle" type="button" data-password-toggle aria-controls="password" aria-pressed="false" aria-label="Tampilkan kata sandi" hidden><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.7"/></svg></button>
        </div>
    </div>
    <label class="checkbox auth-remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Tetap masuk di perangkat ini</label>
    <button class="btn btn-primary auth-submit" type="submit">Masuk ke akun <span aria-hidden="true">→</span></button>
</form>
<p class="switch-auth">Baru di Nyonya Surya? <a href="{{ route('register') }}">Buat akun pelanggan</a></p>
<div class="auth-access-note"><strong>Satu pintu untuk pelanggan & admin</strong><p>Gunakan akun Anda. Pelanggan masuk ke halaman pemesanan, admin ke dashboard pengelolaan.</p></div>
@endsection
