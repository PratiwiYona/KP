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

    <!-- Add Modal -->
    <div class="modal fade" id="addModalBS" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Mobil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" action="{{ route('unitmasuk.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="addNomorRangka" class="form-label">Nomor Rangka:</label>
                            <input type="text" class="form-control" id="addNomorRangka" name="nomor_rangka" required>
                        </div>
                        <div class="mb-3">
                            <label for="addModel" class="form-label">Model:</label>
                            <input type="text" class="form-control" id="addModel" name="model" required>
                        </div>
                        <div class="mb-3">
                            <label for="addWarna" class="form-label">Warna:</label>
                            <input type="text" class="form-control" id="addWarna" name="warna" required>
                        </div>
                        <div class="mb-3">
                            <label for="addTanggalMasuk" class="form-label">Tanggal Masuk:</label>
                            <input type="date" class="form-control" id="addTanggalMasuk" name="tanggal_masuk" required>
                        </div>
                        <div class="mb-3">
                            <label for="addKapalPembawa" class="form-label">Kapal Pembawa:</label>
                            <input type="text" class="form-control" id="addKapalPembawa" name="kapal_pembawa">
                        </div>
                        <div class="mb-3">
                            <label for="addKeteranganStatus" class="form-label">Keterangan Status:</label>
                            <select id="addKeteranganStatus" name="keterangan_status" class="form-select">
                                <option value="" selected>Pilih Status</option>
                                <option value="{{ \App\Constants\Constants::KETERANGAN_MOBIL_DICUCI }}">{{ \App\Constants\Constants::KETERANGAN_MOBIL_DICUCI }}</option>
                                <option value="{{ \App\Constants\Constants::KETERANGAN_MOBIL_DIKERINGKAN }}">{{ \App\Constants\Constants::KETERANGAN_MOBIL_DIKERINGKAN }}</option>
                                <option value="{{ \App\Constants\Constants::KETERANGAN_MOBIL_PARKIR }}">{{ \App\Constants\Constants::KETERANGAN_MOBIL_PARKIR }}</option>
                                <option value="{{ \App\Constants\Constants::KETERANGAN_MOBIL_DEFECT }}">{{ \App\Constants\Constants::KETERANGAN_MOBIL_DEFECT }}</option>
                            </select>
                        </div>
                        <div class="mb-3" id="addKodeParkirContainer" style="display:none;">
                            <label for="addKodeParkir" class="form-label">Kode Parkir:</label>
                            <input type="text" class="form-control" id="addKodeParkir" name="kode_parkir">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModalBS" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Mobil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editId" name="id_mobil">
                        <div class="mb-3">
                            <label for="editNomorRangka" class="form-label">Nomor Rangka:</label>
                            <input type="text" class="form-control" id="editNomorRangka" name="nomor_rangka" required>
                        </div>
                        <div class="mb-3">
                            <label for="editModel" class="form-label">Model:</label>
                            <input type="text" class="form-control" id="editModel" name="model" required>
                        </div>
                        <div class="mb-3">
                            <label for="editWarna" class="form-label">Warna:</label>
                            <input type="text" class="form-control" id="editWarna" name="warna" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTanggalMasuk" class="form-label">Tanggal Masuk:</label>
                            <input type="date" class="form-control" id="editTanggalMasuk" name="tanggal_masuk" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKapalPembawa" class="form-label">Kapal Pembawa:</label>
                            <input type="text" class="form-control" id="editKapalPembawa" name="kapal_pembawa">
                        </div>
                        <div class="mb-3">
                            <label for="editKeteranganStatus" class="form-label">Keterangan Status:</label>
                            <select id="editKeteranganStatus" name="keterangan_status" class="form-select" onchange="toggleKodeParkir('edit')">
                                @php
                                    use App\Constants\Constants;
                                @endphp
                                <option value="{{ Constants::KETERANGAN_MOBIL_DICUCI }}">{{ Constants::KETERANGAN_MOBIL_DICUCI }}</option>
                                <option value="{{ Constants::KETERANGAN_MOBIL_DIKERINGKAN }}">{{ Constants::KETERANGAN_MOBIL_DIKERINGKAN }}</option>
                                <option value="{{ Constants::KETERANGAN_MOBIL_PARKIR }}">{{ Constants::KETERANGAN_MOBIL_PARKIR }}</option>
                                <option value="{{ Constants::KETERANGAN_MOBIL_DEFECT }}">{{ Constants::KETERANGAN_MOBIL_DEFECT }}</option>
                            </select>
                        </div>
                        <div class="mb-3" id="editKodeParkirContainer" style="display:none;">
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

    <!-- Old Modals (Hidden) - Keep for compatibility -->
    <div id="addModal" style="display:none;"></div>
    <div id="editModal" style="display:none;"></div>
    <div id="overlay" style="display:none;"></div>

    <script>
        // Function to toggle kode parkir field visibility
        function toggleKodeParkir(type) {
            const status = document.getElementById(`${type}KeteranganStatus`).value;
            const kodeParkirContainer = document.getElementById(`${type}KodeParkirContainer`);
            if (status === 'Parkir') {
                kodeParkirContainer.style.display = 'block';
            } else {
                kodeParkirContainer.style.display = 'none';
            }
        }

        // Add data functions
        function openAddModal() {
            // Use Bootstrap modal instead of custom modal
            const addModal = new bootstrap.Modal(document.getElementById('addModalBS'));
            addModal.show();
        }

        function closeAddModal() {
            const addModalEl = document.getElementById('addModalBS');
            const addModal = bootstrap.Modal.getInstance(addModalEl);
            if (addModal) {
                addModal.hide();
            }
        }

        // Edit data functions
        function openEditModal(item) {
            const editModal = new bootstrap.Modal(document.getElementById('editModalBS'));

            // Populate form fields with data
            document.getElementById('editId').value = item.id_mobil;
            document.getElementById('editNomorRangka').value = item.nomor_rangka;
            document.getElementById('editModel').value = item.model;
            document.getElementById('editWarna').value = item.warna;
            document.getElementById('editTanggalMasuk').value = item.tanggal_masuk;
            document.getElementById('editKapalPembawa').value = item.kapal_pembawa || '';

            // Set keterangan status
            const select = document.getElementById('editKeteranganStatus');
            if (item.latestKeteranganMobil && item.latestKeteranganMobil.keterangan) {
                select.value = item.latestKeteranganMobil.keterangan.keterangan;
            } else {
                select.value = '';
            }

            // Show/hide kode parkir based on status
            toggleKodeParkir('edit');

            // Set kode parkir value if available
            if (item.latestStatusMobil && item.latestStatusMobil.kode_parkir) {
                document.getElementById('editKodeParkir').value = item.latestStatusMobil.kode_parkir;
            } else {
                document.getElementById('editKodeParkir').value = '';
            }

            // Set form action URL
            document.getElementById('editForm').action = `/unitmasuk/${item.id_mobil}`;

            editModal.show();
        }

        function closeEditModal() {
            const editModalEl = document.getElementById('editModalBS');
            const editModal = bootstrap.Modal.getInstance(editModalEl);
            if (editModal) {
                editModal.hide();
            }
        }

        // Event listeners for keterangan status changes
        document.getElementById('addKeteranganStatus').addEventListener('change', function() {
            const kodeParkirContainer = document.getElementById('addKodeParkirContainer');
            if (this.value === 'Parkir') {
                kodeParkirContainer.style.display = 'block';
            } else {
                kodeParkirContainer.style.display = 'none';
            }
        });
    </script>
</div>
@endsection