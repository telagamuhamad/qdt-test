@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Penjualan Barang</h1>

    <form method="GET" action="{{ route('index') }}" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="name">Nama Barang</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}">
            </div>
            <div class="form-group col-md-2">
                <label for="qty">Stok</label>
                <input type="number" name="qty" id="qty" class="form-control" value="{{ request('qty') }}">
            </div>
            <div class="form-group col-md-2">
                <label for="sold">Jumlah Terjual</label>
                <input type="number" name="sold" id="sold" class="form-control" value="{{ request('sold') }}">
            </div>
            <div class="form-group col-md-2">
                <label for="date">Tanggal Transaksi</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="form-group col-md-2">
                <label for="type">Jenis Barang</label>
                <input type="text" name="type" id="type" class="form-control" value="{{ request('type') }}">
            </div>
            <div class="form-group col-md-2">
                <label for="sort_by">Urutkan</label>
                <select name="sort_by" id="sort_by" class="form-control">
                    <option value="">Select</option>
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama Barang</option>
                    <option value="date" {{ request('sort_by') == 'date' ? 'selected' : '' }}>Tanggal Transaksi</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Nama Barang</th>
                <th>Stok</th>
                <th>Jumlah Terjual</th>
                <th>Tanggal Transaksi</th>
                <th>Jenis Barang</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($stockSales->isEmpty())
                <tr>
                    <td colspan="7">Tidak Ada Data.</td>
                </tr>
            @endif
            @foreach($stockSales as $stockSale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stockSale->id }}</td>
                    <td>{{ $stockSale->stock->name ?? '' }}</td>
                    <td>{{ number_format($stockSale->stock_qty, 0) }}</td>
                    <td>{{ number_format($stockSale->stock_sold, 0) }}</td>
                    <td>{{ $stockSale->formatted_transaction_date ?? '' }}</td>
                    <td>{{ $stockSale->stock->type->type_name ?? '' }}</td>
                    <td>
                        <a href="{{ route('show', $stockSale->id) }}" class="btn btn-primary">Lihat</a>
                        <form action="{{ route('destroy', $stockSale->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<hr>
<div class="container">
    <h1>Bandingkan Penjualan</h1>

    <form method="GET" action="{{ route('index') }}" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="date_from">Transaksi Dari</label>
                <input type="date" name="date_from" id="date_from" class="form-control">
            </div>
            <div class="form-group col-md-2">
                <label for="date_to">Transaksi Sampai</label>
                <input type="date" name="date_to" id="date_to" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Bandingkan</button>
    </form>

    <h2>Tertinggi</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah Terjual</th>
                <th>Tanggal Transaksi</th>
                <th>Jenis Barang</th>
            </tr>
        </thead>
        <tbody>
            @if ($mostSold)
                <tr>
                    <td>{{ $mostSold->stock->name }}</td>
                    <td>{{ $mostSold->stock_sold }}</td>
                    <td>{{ $mostSold->formatted_transaction_date }}</td>
                    <td>{{ $mostSold->stock->type->type_name }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="4">Tidak ada data transaksi pada rentang waktu yang dipilih.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <h2>Terendah</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah Terjual</th>
                <th>Tanggal Transaksi</th>
                <th>Jenis Barang</th>
            </tr>
        </thead>
        <tbody>
            @if ($leastSold)
                <tr>
                    <td>{{ $leastSold->stock->name }}</td>
                    <td>{{ $leastSold->stock_sold }}</td>
                    <td>{{ $leastSold->formatted_transaction_date }}</td>
                    <td>{{ $leastSold->stock->type->type_name }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="4">Tidak ada data transaksi pada rentang waktu yang dipilih.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection