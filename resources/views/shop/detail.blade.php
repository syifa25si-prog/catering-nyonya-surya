@extends('layouts.app')
@section('title',$menu->name)
@section('content')
<a class="back-link" href="{{ route('catalog') }}">← Kembali ke katalog</a><section class="detail-grid"><div class="detail-image"><img src="{{ $menu->imageUrl() }}" alt="{{ $menu->name }}"></div><div class="detail-copy"><span class="eyebrow">{{ $menu->category->name }}</span><h1>{{ $menu->name }}</h1><span class="status status-selesai">Tersedia untuk dipesan</span><p class="description">{{ $menu->description }}</p><div class="price">Rp {{ number_format($menu->price,0,',','.') }}<small> / porsi</small></div><form method="post" action="{{ route('cart.store',$menu) }}" class="stack">@csrf<label>Jumlah porsi<input class="form-control quantity" name="quantity" type="number" min="1" max="9999" value="{{ old('quantity',1) }}" required></label><button class="btn btn-primary">Tambahkan ke keranjang →</button></form><p class="muted">Tanggal acara dan alamat diisi saat checkout.</p></div></section>
@endsection
