<nav class="category-chips" aria-label="Kategori paket catering">
    <a class="category-chip {{ !request('category') && request('q') !== 'Tumpeng' ? 'is-active' : '' }}" href="{{ route('catalog', request('q') && request('q') !== 'Tumpeng' ? ['q' => request('q')] : []) }}" @if(!request('category') && request('q') !== 'Tumpeng') aria-current="page" @endif>Semua Menu</a>
    @foreach($categories as $category)
        <a class="category-chip {{ (string)request('category') === (string)$category->id ? 'is-active' : '' }}" href="{{ route('catalog', ['category' => $category->id] + (request('q') && request('q') !== 'Tumpeng' ? ['q' => request('q')] : [])) }}" @if((string)request('category') === (string)$category->id) aria-current="page" @endif>{{ $category->name }}</a>
    @endforeach
    @unless($categories->contains('name', 'Tumpeng'))
        <a class="category-chip {{ request('q') === 'Tumpeng' && !request('category') ? 'is-active' : '' }}" href="{{ route('catalog', ['q' => 'Tumpeng']) }}" @if(request('q') === 'Tumpeng' && !request('category')) aria-current="page" @endif>Tumpeng</a>
    @endunless
</nav>
