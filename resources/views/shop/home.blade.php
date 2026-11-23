@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="hero ordering-hero">
        <div class="hero-copy">
            <span class="eyebrow"><span class="tiny-dot"></span> CATERING NYONYA SURYA</span>
            <h1>Sajian favorit,<br>untuk setiap <em>momen istimewa.</em></h1>
            <p>Dari nasi kotak hingga prasmanan. Temukan hidangan untuk acara Anda, pilih porsinya, dan pesan dalam satu tempat.</p>

            <form class="hero-search" action="{{ route('catalog') }}" method="get" role="search">
                <label for="hero-search" class="visually-hidden">Cari paket catering</label>
                <input id="hero-search" name="q" type="search" maxlength="100" placeholder="Lagi ingin pesan apa? Cari menu di sini…" class="form-control">
                <button class="btn btn-primary" type="submit">Cari menu</button>
            </form>

            <div class="actions">
                <a class="hero-browse" href="{{ route('catalog') }}">Lihat semua menu →</a>
                <a class="hero-browse" href="{{ route('orders.index') }}">Pantau pesanan saya →</a>
            </div>

            <div class="hero-note">
                <span>✓ Banyak pilihan paket</span>
                <span>✓ Mudah dipesan</span>
                <span>✓ Status pesanan jelas</span>
            </div>
        </div>

        <div class="hero-art">
            <span class="circle-caption">CITA RASA RUMAHAN</span>
            <img src="{{ asset('images/menus/prasmanan-istimewa.webp') }}" alt="Ilustrasi penyajian prasmanan Nusantara dengan rendang, ayam panggang, sayuran, buah, dan puding" width="1200" height="800" fetchpriority="high" decoding="async">
            <div class="floating-label">
                <span>✦</span>
                <div>
                    Satu sajian, banyak cerita
                    <small>Untuk setiap momen kebersamaan.</small>
                </div>
            </div>
        </div>
    </section>

    <section class="category-section" aria-label="Pilih kategori menu">
        <div class="section-heading">
            <div>
                <span class="eyebrow">PILIH SESUAI KEBUTUHAN</span>
                <h2>Mau pesan apa hari ini?</h2>
            </div>
        </div>
        @include('components.category-chips')
    </section>

    <section class="section menu-section">
        <div class="section-heading">
            <div>
                <span class="eyebrow">PILIHAN DARI DAPUR KAMI</span>
                <h2>Paket untuk momen Anda</h2>
            </div>
            <a class="quiet-link" href="{{ route('catalog') }}">Lihat semua menu →</a>
        </div>

        <div class="menu-grid">
            @forelse($menus as $menu)
                @include('components.menu-card')
            @empty
                <div class="empty-state">
                    <h3>Menu sedang disiapkan.</h3>
                    <p>Paket catering akan segera tersedia.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="steps-strip">
        <div>
            <span>01</span>
            <p>
                <strong>Pilih menu favorit</strong>
                <small>Temukan paket sesuai kebutuhan.</small>
            </p>
        </div>
        <div>
            <span>02</span>
            <p>
                <strong>Atur detail acara</strong>
                <small>Tentukan porsi, alamat, dan tanggal.</small>
            </p>
        </div>
        <div>
            <span>03</span>
            <p>
                <strong>Pantau pesanan</strong>
                <small>Lihat statusnya dari akun Anda.</small>
            </p>
        </div>
    </section>

    <section class="warm-banner">
        <div>
            <span class="eyebrow">UNTUK ACARA KECIL HINGGA HARI ISTIMEWA</span>
            <h2>Acara Anda, sajiannya kami bantu.</h2>
            <p>Pilih paket catering dan lengkapi detail pemesanan dengan mudah.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('catalog') }}">Pesan sekarang →</a>
    </section>
@endsection