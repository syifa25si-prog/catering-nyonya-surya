@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <div class="page-heading">
        <span class="eyebrow">LANGKAH PERTAMA MENUJU MOMEN ISTIMEWA</span>
        <h1>Keranjang Anda.</h1>
        <p>Atur pilihan sajian sebelum melanjutkan pemesanan.</p>
    </div>

    @foreach($missing as $missingId)
        <div class="notice danger">
            <p>Paket yang sebelumnya dipilih telah dihapus dari katalog.</p>
            <form method="post" action="{{ route('cart.destroy', $missingId) }}">
                @csrf
                @method('DELETE')
                <button class="text-button">Hapus dari keranjang</button>
            </form>
        </div>
    @endforeach

    @if($items->isEmpty())
        <div class="empty-state">
            <h2>Keranjang masih kosong.</h2>
            <p>Temukan paket yang cocok untuk acara Anda.</p>
            <a class="btn btn-primary" href="{{ route('catalog') }}">Jelajahi paket →</a>
        </div>
    @else
        @if($unavailable)
            <div class="notice danger">
                Ada paket yang tidak tersedia atau telah dihapus. Hapus paket tersebut atau kosongkan pilihan yang tersisa sebelum memesan kembali.
            </div>
        @endif

        <div class="checkout-grid">
            <div class="panel">
                @foreach($items as $item)
                    <article class="cart-row">
                        <img src="{{ $item['menu']->imageUrl() }}" alt="{{ $item['menu']->name }}">

                        <div class="cart-description">
                            <h3>{{ $item['menu']->name }}</h3>
                            <p>Rp {{ number_format($item['menu']->price, 0, ',', '.') }} / porsi</p>

                            @unless($item['menu']->is_available)
                                <span class="status status-dibatalkan">Tidak tersedia</span>
                            @endunless

                            <form action="{{ route('cart.destroy', $item['menu']->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="text-button danger-text">Hapus paket</button>
                            </form>
                        </div>

                        <div>
                            <form action="{{ route('cart.update', $item['menu']) }}" method="post" class="quantity-form">
                                @csrf
                                @method('PATCH')
                                <label>
                                    <span class="visually-hidden">Jumlah {{ $item['menu']->name }}</span>
                                    <input class="form-control quantity" type="number" name="quantity" min="1" max="9999" value="{{ $item['quantity'] }}" required>
                                </label>
                                <button class="text-button">Perbarui</button>
                            </form>
                            <strong>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                        </div>
                    </article>
                @endforeach

                <a class="quiet-link" href="{{ route('catalog') }}">← Tambah paket lainnya</a>
            </div>

            <aside class="panel summary">
                <span class="eyebrow">PILIHAN ANDA</span>
                <h2>Ringkasan pesanan</h2>
                <div class="summary-line">
                    <span>Jumlah porsi</span>
                    <strong>{{ $items->sum('quantity') }}</strong>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                </div>

                @unless($unavailable)
                    <a class="btn btn-primary" href="{{ route('checkout') }}">Lanjut pemesanan →</a>
                @endunless

                <p class="muted">Periksa jumlah dan pilihan paket sebelum melanjutkan.</p>
            </aside>
        </div>
    @endif
@endsection