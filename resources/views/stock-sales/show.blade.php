@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Data</h1>

    @if(session('error_message'))
        <div class="alert alert-danger">
            {{ session('error_message') }}
        </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success">
            {{ session('success_message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('update', $stockSales->id) }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Barang</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $stockSales->stock->name) }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="type">Jenis Barang</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">Select Type</option>
                @foreach($stockTypes as $type)
                    <option value="{{ $type->type_name }}" {{ old('type', $stockSales->stock->type->type_name) == $type->type_name ? 'selected' : '' }}>{{ $type->type_name }}</option>
                @endforeach
            </select>
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="stock_qty">Stok</label>
            <input type="number" name="stock_qty" id="stock_qty" class="form-control" value="{{ old('stock_qty', $stockSales->stock_qty) }}" required>
            @error('stock_qty')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="stock_sold">Jumlah Terjual</label>
            <input type="number" name="stock_sold" id="stock_sold" class="form-control" value="{{ old('stock_sold', $stockSales->stock_sold) }}" required>
            @error('stock_sold')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="transaction_date">Tanggal Transaksi</label>
            <input type="date" name="transaction_date" id="transaction_date" class="form-control" value="{{ old('transaction_date', $stockSales->transaction_date) }}" required step="0.01">
            @error('transaction_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
