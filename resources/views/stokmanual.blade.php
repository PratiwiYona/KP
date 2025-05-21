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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModalBS" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="editModalLabel">Edit Update Mobil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('stokmanual.update', $item->id_mobil) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editIdKondisi" name="id_kondisi">

                        <div class="mb-3">
                            <label for="editKeterangan" class="form-label">Keterangan:</label>
                            <select id="editKeterangan" name="keterangan" class="form-select">
                                <option value="Sudah Diperbaiki">Sudah Diperbaiki</option>
                                <option value="Parkir">Parkir</option>
                                <option value="Defect">Defect</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editKodeParkir" class="form-label">Kode Parkir:</label>
                            <input type="text" class="form-control" id="editKodeParkir" name="kode_parkir">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Old Edit Modal (Hidden) - Keep for compatibility -->
    <div id="editModal" style="display:none;">
        <!-- This is kept for compatibility with the existing JavaScript -->
    </div>
    <div id="overlay" style="display:none;"></div>

    <!-- Bootstrap JS -->
    <script>
        // Keep original functions for compatibility
        function openEditModal(item) {
            // Instead of showing the old modal, trigger the Bootstrap modal
            const editModal = new bootstrap.Modal(document.getElementById('editModalBS'));
            
            // Populate form fields with data
            document.getElementById('editIdKondisi').value = item ? item.id_kondisi : '';
            document.getElementById('editKodeParkir').value = item && item.kode_parkir ? item.kode_parkir : '';
            
            if (item && item.keterangan) {
                const select = document.getElementById('editKeterangan');
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value === item.keterangan) {
                        select.selectedIndex = i;
                        break;
                    }
                }
            }

            // Set form action URL if item exists
            if (item && item.id_mobil) {
                document.getElementById('editForm').action = `/stokmanual/${item.id_mobil}`;
            } else {
                console.error('ID Mobil tidak ditemukan untuk item:', item);
                alert('Data tidak valid. Silakan coba lagi.');
            }
            
            editModal.show();
        }

        function closeEditModal() {
            // This function remains for compatibility but uses Bootstrap's modal hiding
            const editModalEl = document.getElementById('editModalBS');
            const editModal = bootstrap.Modal.getInstance(editModalEl);
            if (editModal) {
                editModal.hide();
            }
        }
    </script>
</div>
@endsection