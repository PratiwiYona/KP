@extends('layouts.main')

@section('container')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold" style="color:rgb(81, 135, 190)">Unit Problem</h2>
            <p class="text-muted">Pengelolaan data unit kendaraan bermasalah</p>
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
                            <th>Catatan Defect</th>
                            <th>Tanggal Masuk Bengkel</th>
                            <th>Tanggal Keluar Bengkel</th>
                            <th>Klaim Warranty</th>
                            <th>Update Kondisi Unit</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unitProblems as $index => $problem)
                            <tr>
                                @if ($problem === null || $problem->latestKondisiMobil === null)
                                    @continue
                                @endif
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $problem->nomor_rangka }}</td>
                                <td>{{ $problem->model }}</td>
                                <td>
                                    @if(isset($problem->latestKondisiMobil->catatan_defect) && !empty($problem->latestKondisiMobil->catatan_defect))
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $problem->latestKondisiMobil->catatan_defect }}">
                                            {{ $problem->latestKondisiMobil->catatan_defect }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $problem->latestKondisiMobil->tanggal_masuk_bengkel ?? '-' }}</td>
                                <td>{{ $problem->latestKondisiMobil->tanggal_keluar_bengkel ?? '-' }}</td>
                                <td>{{ $problem->latestKondisiMobil->klaim_warranty ?? '-' }}</td>
                                <td>
                                    @if(isset($problem->latestKeteranganMobil->keterangan->keterangan))
                                        <span class="badge bg-info text-dark">{{ $problem->latestKeteranganMobil->keterangan->keterangan }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Button Edit -->
                                        <button class="btn btn-sm btn-primary" onclick="openEditModal({{ $problem->latestKondisiMobil }})">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>

                                        <!-- Button Delete -->
                                        <form action="{{ route('unitproblem.destroy', $problem->latestKondisiMobil->id_kondisi) }}" method="POST" class="d-inline">
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

    <!-- Bootstrap Edit Modal -->
    <div class="modal fade" id="editModalBS" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="editModalLabel">Edit Kondisi Mobil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editIdKondisi" name="editIdKondisi">
                        
                        <div class="mb-3">
                            <label for="editCatatanDefect" class="form-label">Catatan Defect:</label>
                            <textarea class="form-control" id="editCatatanDefect" name="catatan_defect" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editTanggalMasukBengkel" class="form-label">Tanggal Masuk Bengkel:</label>
                            <input type="date" class="form-control" id="editTanggalMasukBengkel" name="tanggal_masuk_bengkel">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editTanggalKeluarBengkel" class="form-label">Tanggal Keluar Bengkel:</label>
                            <input type="date" class="form-control" id="editTanggalKeluarBengkel" name="tanggal_keluar_bengkel">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editKlaimWarranty" class="form-label">Klaim Warranty:</label>
                            <textarea class="form-control" id="editKlaimWarranty" name="klaim_warranty" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editUpdateKondisiUnit" class="form-label">Update Kondisi Unit:</label>
                            <select class="form-select" id="editUpdateKondisiUnit" name="update_kondisi_unit">
                                <option value="Maintenance">Maintenance</option>
                                <option value="Sudah Diperbaiki">Sudah Diperbaiki</option>
                            </select>
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

    <!-- Keep original modal for compatibility -->
    <div id="editModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid #ccc; z-index:1000;">
        <!-- This is kept empty for compatibility with existing JavaScript -->
    </div>
    <div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;" onclick="closeEditModal()"></div>

    <script>
        function openEditModal(problem) {
            // Instead of showing the original modal, trigger the Bootstrap modal
            const editModal = new bootstrap.Modal(document.getElementById('editModalBS'));
            
            // Populate form fields with data
            document.getElementById('editIdKondisi').value = problem.id_kondisi;
            document.getElementById('editCatatanDefect').value = problem.catatan_defect || '';
            document.getElementById('editTanggalMasukBengkel').value = problem.tanggal_masuk_bengkel || '';
            document.getElementById('editTanggalKeluarBengkel').value = problem.tanggal_keluar_bengkel || '';
            document.getElementById('editKlaimWarranty').value = problem.klaim_warranty || '';
            document.getElementById('editUpdateKondisiUnit').value = problem.update_kondisi_unit || 'Maintenance';

            // Set form action URL
            document.getElementById('editForm').action = '/unitproblem/' + problem.id_kondisi;
            
            // Show the Bootstrap modal
            editModal.show();
            
            // Also update the original modal (keeping for compatibility)
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeEditModal() {
            // Close both the original and Bootstrap modals
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
            
            // Close Bootstrap modal if it exists
            const editModalEl = document.getElementById('editModalBS');
            const editModal = bootstrap.Modal.getInstance(editModalEl);
            if (editModal) {
                editModal.hide();
            }
        }
    </script>
</div>
@endsection