<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · Nyonya Surya</title>
    <link rel="stylesheet" href="{{ asset('vendor/volt/volt.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=auth-fit-9">
    <script src="{{ asset('js/auth.js') }}" defer></script>
</head>
<body class="auth-page auth-redesign {{ request()->routeIs('login') ? 'auth-login-page' : '' }}">
    <main class="auth-shell">
        <section class="auth-story" aria-label="Catering Nyonya Surya">
            <div class="brand light"><span class="brand-mark">NS</span><span>Nyonya Surya<small>CATERING & HOSPITALITY</small></span></div>
            <div class="auth-story-copy">
                <span class="auth-pill">SAJIAN PILIHAN UNTUK SETIAP MOMEN</span>
                <h1>Momen istimewa.<br>Sajian yang<br><span>selalu berkesan.</span></h1>
                <p>Dari hidangan keluarga hingga acara bersama. Temukan paket catering yang tepat, dalam satu tempat.</p>
            </div>
            <figure class="auth-food">
                <img src="{{ asset('images/menus/prasmanan-istimewa.webp') }}" alt="Sajian prasmanan Nusantara dengan pilihan lauk dan hidangan pelengkap" width="1200" height="800" fetchpriority="high">
                <figcaption><span class="auth-food-icon" aria-hidden="true">✦</span><div>Cita rasa rumahan<small>Disiapkan untuk momen kebersamaan.</small></div></figcaption>
            </figure>
            <div class="auth-benefits"><span>✓ Pilih paket favorit</span><span>✓ Pesan dengan mudah</span><span>✓ Pantau pesanan</span></div>
        </section>
        <section class="auth-form" aria-label="@yield('title')">
            <div class="auth-form-top"><span class="auth-mini-mark">NS</span><span>SELAMAT DATANG DI NYONYA SURYA</span></div>
            @yield('content')
            <div class="auth-bottom"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 10V7a6 6 0 0 1 12 0v3M5 10h14v11H5z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg><span>Akses akun dan kelola pesanan Anda dengan nyaman.</span></div>
            <p class="auth-copyright">© {{ date('Y') }} Catering Nyonya Surya</p>
        </section>
    </main>
</body>
</html>
