@extends('layouts.app')
@section('title',$category->exists?'Edit kategori':'Tambah kategori')
@section('content')
<a class="back-link" href="{{ route('admin.categories.index') }}">← Kembali ke kategori</a><div class="page-heading"><h1>{{ $category->exists?'Edit':'Tambah' }} kategori.</h1></div><form class="panel stack form-narrow" method="post" action="{{ $category->exists?route('admin.categories.update',$category):route('admin.categories.store') }}">@csrf @if($category->exists)@method('PUT')@endif<label>Nama kategori<input class="form-control" name="name" value="{{ old('name',$category->name) }}" maxlength="100" required></label><label>Deskripsi <small>(opsional)</small><textarea class="form-control" name="description" rows="4" maxlength="1000">{{ old('description',$category->description) }}</textarea></label><div class="actions"><button class="btn btn-primary">Simpan kategori</button><a href="{{ route('admin.categories.index') }}">Batal</a></div></form>
@endsection
