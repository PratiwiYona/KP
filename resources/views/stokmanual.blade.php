@extends('layouts.main')

@section('container')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold" style="color:rgb(81, 135, 190)">Stok Manual</h2>
            <p class="text-muted">Pengelolaan data stok kendaraan</p>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card shadow-lg" style="border-radius: 8px;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr class="custom-table-header">
                            <th width="50">No</th>
                            <th>Nomor Rangka</th>
                            <th>Model</th>
                            <th>Warna</th>
                            <th>Tanggal Masuk</th>
                            <th>Kapal Pembawa</th>
                            <th>Keterangan</th>
                            <th>Kode Parkir</th>
                            <th>Catatan Defect</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mobil as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nomor_rangka }}</td>
                                <td>{{ $item->model }}</td>
                                <td>{{ $item->warna }}</td>
                                <td>{{ $item->tanggal_masuk }}</td>
                                <td>{{ $item->kapal_pembawa }}</td>
                                <td>
                                    @if(isset($item->latestKeteranganMobil->keterangan->keterangan))
                                        <span class="badge bg-info text-dark">{{ $item->latestKeteranganMobil->keterangan->keterangan }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->latestStatusMobil->kode_parkir ?? '-' }}</td>
                                <td>{{ $item->latestKondisiMobil->catatan_defect ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Button Edit -->
                                        <button class="btn btn-sm btn-primary" onclick="openEditModal({{ json_encode($item) }})">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>

                                        <!-- Button Delete -->
                                        <form action="{{ route('stokmanual.destroy', $item->id_mobil) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger ms-1" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(item) {
            if (!item || !item.id_mobil) {
                Swal.fire('Error', 'Data tidak valid', 'error');
                return;
            }

            // Siapkan nilai keterangan dan kode parkir
            let kodeParkir = '';
            if (item.latestStatusMobil && item.latestStatusMobil.kode_parkir) {
                kodeParkir = item.latestStatusMobil.kode_parkir;
            } else if (item.kode_parkir) {
                kodeParkir = item.kode_parkir;
            }
            kodeParkir = kodeParkir !== '-' ? kodeParkir : '';

            let keteranganValue = '';
            if (item.latestKeteranganMobil?.keterangan?.keterangan) {
                keteranganValue = item.latestKeteranganMobil.keterangan.keterangan;
            } else if (item.keterangan) {
                keteranganValue = item.keterangan;
            }

            Swal.fire({
                title: 'Edit Update Mobil',
                html: `
                    <form id="editForm" method="POST" action="/stokmanual/${item.id_mobil}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editIdKondisi" name="id_kondisi" value="${item.id_kondisi || ''}">
                        
                        <div class="mb-3 text-start">
                            <label for="editKeterangan" class="form-label">Keterangan:</label>
                            <select id="editKeterangan" name="keterangan" class="form-select">
                                <option value="Sudah Diperbaiki" ${keteranganValue === 'Sudah Diperbaiki' ? 'selected' : ''}>Sudah Diperbaiki</option>
                                <option value="Parkir" ${keteranganValue === 'Parkir' ? 'selected' : ''}>Parkir</option>
                                <option value="Defect" ${keteranganValue === 'Defect' ? 'selected' : ''}>Defect</option>
                            </select>
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editKodeParkir" class="form-label">Kode Parkir:</label>
                            <input type="text" class="form-control" id="editKodeParkir" name="kode_parkir" value="${kodeParkir}">
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                focusConfirm: false,
                customClass: {
                    container: 'my-swal'
                },
                preConfirm: () => {
                    // Submit form
                    document.getElementById('editForm').submit();
                }
            });
        }
    </script>

    <!-- CSS untuk styling form di dalam SweetAlert -->
    <style>
        .my-swal {
            z-index: 99999;
        }
        
        .my-swal .form-label {
            float: left;
        }
        
        .my-swal .form-control,
        .my-swal .form-select {
            margin-bottom: 1rem;
        }
    </style>
</div>
@endsection