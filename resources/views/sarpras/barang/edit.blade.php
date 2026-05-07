@extends('layouts.sarpras')

@section('title', 'Edit Barang')

@section('content')

<div class="container-fluid">
    <h4 class="mb-3">Edit Barang</h4>

    <form method="POST"
          action="{{ route('sarpras.barang.update', $barang->id_barang) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label>Nama Barang</label>
                    <input type="text" name="nama_barang"
                           value="{{ $barang->nama_barang }}"
                           class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-control">
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}"
                                {{ $barang->id_kategori == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Stok</label>
                    <input type="number" name="stok"
                           value="{{ $barang->stok }}"
                           class="form-control" min="0">
                </div>

                <div class="col-md-3">
                    <label>Ganti Gambar (opsional)</label>
                    <input type="file" name="gambar" class="form-control">
                </div>

                <div class="col-12 mt-3">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('sarpras.barang.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection
