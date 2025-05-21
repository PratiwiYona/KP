@extends('layouts.main')

@section('container')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold" style="color:rgb(81, 135, 190)">Unit Masuk</h2>
                <p class="text-muted mb-0">Pengelolaan data unit kendaraan masuk</p>
            </div>
            <div>
                <button class="btn btn-success" onclick="openAddModal()">
                    <i class="bi bi-plus-circle"></i> Tambah Data Mobil
                </button>
            </div>
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
                            <th>Kode Parkir</th>
                            <th>Keterangan</th>
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
                                <td>{{ $item->kapal_pembawa ?? '-' }}</td>
                                <td>{{ $item->latestStatusMobil->kode_parkir ?? '-' }}</td>
                                <td>
                                    @if(isset($item->latestKeteranganMobil->keterangan->keterangan))
                                        <span class="badge bg-info text-dark">{{ $item->latestKeteranganMobil->keterangan->keterangan }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Button Edit -->
                                        <button class="btn btn-sm btn-primary" onclick="openEditModal({{ json_encode($item) }})">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>

                                        <!-- Button Delete -->
                                        <form action="{{ route('unitmasuk.destroy', $item->id_mobil) }}" method="POST" class="d-inline">
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
        function openAddModal() {
            Swal.fire({
                title: 'Tambah Data Mobil',
                html: `
                    <form id="addForm" action="{{ route('unitmasuk.store') }}" method="POST">
                        @csrf
                        <div class="mb-3 text-start">
                            <label for="addNomorRangka" class="form-label">Nomor Rangka:</label>
                            <input type="text" class="form-control" id="addNomorRangka" name="nomor_rangka" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="addModel" class="form-label">Model:</label>
                            <input type="text" class="form-control" id="addModel" name="model" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="addWarna" class="form-label">Warna:</label>
                            <input type="text" class="form-control" id="addWarna" name="warna" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="addTanggalMasuk" class="form-label">Tanggal Masuk:</label>
                            <input type="date" class="form-control" id="addTanggalMasuk" name="tanggal_masuk" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="addKapalPembawa" class="form-label">Kapal Pembawa:</label>
                            <input type="text" class="form-control" id="addKapalPembawa" name="kapal_pembawa">
                        </div>
                        <div class="mb-3 text-start">
                            <label for="addKeteranganStatus" class="form-label">Keterangan Status:</label>
                            <select id="addKeteranganStatus" name="keterangan_status" class="form-select" onchange="toggleKodeParkir('add')">
                                <option value="" selected>Pilih Status</option>
                                <option value="{{ \App\Constants\Constants::KETERANGAN_MOBIL_DICUCI }}">{{ \App\Constants\Constants::KETERANGAN_MOBIL_DICUCI }}</option>
                                <option value="{{ \App\Constants\Constants::KETERANGAN_MOBIL_DIKERINGKAN }}">{{ \App\Constants\Constants::KETERANGAN_MOBIL_DIKERINGKAN }}</option>
                                <option value="{{ \App\Constants\Constants::KETERANGAN_MOBIL_PARKIR }}">{{ \App\Constants\Constants::KETERANGAN_MOBIL_PARKIR }}</option>
                                <option value="{{ \App\Constants\Constants::KETERANGAN_MOBIL_DEFECT }}">{{ \App\Constants\Constants::KETERANGAN_MOBIL_DEFECT }}</option>
                            </select>
                        </div>
                        <div class="mb-3 text-start" id="addKodeParkirContainer" style="display:none;">
                            <label for="addKodeParkir" class="form-label">Kode Parkir:</label>
                            <input type="text" class="form-control" id="addKodeParkir" name="kode_parkir">
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                customClass: {
                    container: 'my-swal'
                },
                didOpen: () => {
                    // Reinitialize toggle function for kode parkir
                    document.getElementById('addKeteranganStatus').addEventListener('change', function() {
                        const kodeParkirContainer = document.getElementById('addKodeParkirContainer');
                        kodeParkirContainer.style.display = this.value === 'Parkir' ? 'block' : 'none';
                    });
                },
                preConfirm: () => {
                    // Submit form
                    document.getElementById('addForm').submit();
                }
            });
        }

        // Function to toggle kode parkir field visibility
        function toggleKodeParkir(type) {
            const status = document.getElementById(`${type}KeteranganStatus`).value;
            const kodeParkirContainer = document.getElementById(`${type}KodeParkirContainer`);
            kodeParkirContainer.style.display = status === 'Parkir' ? 'block' : 'none';
        }

        // Edit data functions
        function openEditModal(item) {
            if (!item || !item.id_mobil) {
                Swal.fire('Error', 'Data tidak valid', 'error');
                return;
            }

            Swal.fire({
                title: 'Edit Data Mobil',
                html: `
                    <form id="editForm" method="POST" action="/unitmasuk/${item.id_mobil}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editId" name="id_mobil" value="${item.id_mobil}">
                        
                        <div class="mb-3 text-start">
                            <label for="editNomorRangka" class="form-label">Nomor Rangka:</label>
                            <input type="text" class="form-control" id="editNomorRangka" name="nomor_rangka" value="${item.nomor_rangka}" required>
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editModel" class="form-label">Model:</label>
                            <input type="text" class="form-control" id="editModel" name="model" value="${item.model}" required>
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editWarna" class="form-label">Warna:</label>
                            <input type="text" class="form-control" id="editWarna" name="warna" value="${item.warna}" required>
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editTanggalMasuk" class="form-label">Tanggal Masuk:</label>
                            <input type="date" class="form-control" id="editTanggalMasuk" name="tanggal_masuk" value="${item.tanggal_masuk}" required>
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editKapalPembawa" class="form-label">Kapal Pembawa:</label>
                            <input type="text" class="form-control" id="editKapalPembawa" name="kapal_pembawa" value="${item.kapal_pembawa || ''}">
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editKeteranganStatus" class="form-label">Keterangan Status:</label>
                            <select id="editKeteranganStatus" name="keterangan_status" class="form-select" onchange="toggleKodeParkir('edit')">
                                <option value="Dicuci" ${item.latestKeteranganMobil?.keterangan?.keterangan === 'Dicuci' ? 'selected' : ''}>Dicuci</option>
                                <option value="Dikeringkan" ${item.latestKeteranganMobil?.keterangan?.keterangan === 'Dikeringkan' ? 'selected' : ''}>Dikeringkan</option>
                                <option value="Parkir" ${item.latestKeteranganMobil?.keterangan?.keterangan === 'Parkir' ? 'selected' : ''}>Parkir</option>
                                <option value="Defect" ${item.latestKeteranganMobil?.keterangan?.keterangan === 'Defect' ? 'selected' : ''}>Defect</option>
                            </select>
                        </div>
                        
                        <div class="mb-3 text-start" id="editKodeParkirContainer" style="display:${item.latestKeteranganMobil?.keterangan?.keterangan === 'Parkir' ? 'block' : 'none'}">
                            <label for="editKodeParkir" class="form-label">Kode Parkir:</label>
                            <input type="text" class="form-control" id="editKodeParkir" name="kode_parkir" value="${item.latestStatusMobil?.kode_parkir || ''}">
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                customClass: {
                    container: 'my-swal'
                },
                didOpen: () => {
                    // Reinitialize toggle function for kode parkir
                    document.getElementById('editKeteranganStatus').addEventListener('change', function() {
                        const kodeParkirContainer = document.getElementById('editKodeParkirContainer');
                        kodeParkirContainer.style.display = this.value === 'Parkir' ? 'block' : 'none';
                    });
                },
                preConfirm: () => {
                    // Submit form
                    document.getElementById('editForm').submit();
                }
            });
        }
    </script>

    <style>
        .my-swal {
            z-index: 99999;
        }
        
        .my-swal .form-label {
            float: left;
        }
        
        .my-swal .form-control,
        .my-swal .form-select {
            margin-bottom: 0.5rem;
        }
    </style>
</div>
@endsection