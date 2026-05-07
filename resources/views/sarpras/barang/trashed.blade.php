@extends('layouts.sarpras')

@section('title', 'Barang Terhapus')

@section('content')

<div class="container-fluid">

    <h4 class="mb-3">🗑️ Barang Terhapus</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between">
            <span>Daftar Barang Terhapus</span>

            <a href="{{ route('sarpras.barang.index') }}" class="btn btn-sm btn-secondary">
                ← Kembali
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($barang as $b)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $b->stok }}</td>
                        <td>
            @php
                $path = public_path('gambar_barang/' . $b->gambar);
            @endphp

            <img
                src="{{ (!empty($b->gambar) && file_exists($path))
                    ? asset('gambar_barang/' . $b->gambar)
                    : asset('gambar_barang/default.png')
                }}"
                width="45"
                alt="{{ $b->nama_barang }}"
            >
                        </td>
                        <td class="text-nowrap">

                            {{-- RESTORE --}}
                            <form method="POST"
                                  action="{{ route('sarpras.barang.restore', $b->id_barang) }}"
                                  class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">
                                    Pulihkan
                                </button>
                            </form>

                            {{-- DELETE PERMANEN --}}
                            <form method="POST"
                                  action="{{ route('sarpras.barang.force', $b->id_barang) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus PERMANEN? Data tidak bisa dikembalikan!')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    Hapus Permanen
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Tidak ada barang di tempat sampah
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $barang->links() }}
        </div>
    </div>

</div>

@endsection
