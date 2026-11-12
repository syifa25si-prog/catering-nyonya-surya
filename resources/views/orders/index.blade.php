@extends('layouts.app')
@section('title','Pesanan saya')
@section('content')
<div class="page-heading"><span class="eyebrow">SEMUA MOMEN ANDA, DALAM SATU TEMPAT</span><h1>Pesanan saya.</h1><p>Lihat riwayat dan pantau status pesanan catering Anda.</p></div><div class="panel">@include('components.order-table',['admin'=>false])</div>{{ $orders->links('pagination::bootstrap-5') }}
@endsection
