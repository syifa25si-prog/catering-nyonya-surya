<article class="menu-card">
    <a href="{{ route('menu.show',$menu) }}"><div class="menu-image"><img src="{{ $menu->imageUrl() }}" alt="{{ $menu->name }}" loading="lazy" width="1200" height="800"><span class="image-tag">{{ $menu->category->name }}</span></div></a>
    <div class="menu-body">
        <h3><a href="{{ route('menu.show',$menu) }}">{{ $menu->name }}</a></h3>
        <p>{{ Str::limit($menu->description,100) }}</p>
        <div class="menu-bottom"><div><small>Harga per porsi</small><strong>Rp {{ number_format($menu->price,0,',','.') }}</strong></div></div>
        <div class="menu-card-actions">
            <a href="{{ route('menu.show',$menu) }}" class="menu-detail-link">Lihat detail</a>
            <form method="post" action="{{ route('cart.store',$menu) }}">@csrf<input type="hidden" name="quantity" value="1"><button class="btn btn-primary add-menu-button" type="submit" aria-label="Tambah satu porsi {{ $menu->name }} ke keranjang">+ Tambah</button></form>
        </div>
    </div>
</article>
