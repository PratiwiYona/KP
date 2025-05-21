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

    <script>
        function openEditModal(problem) {
            if (!problem || !problem.id_kondisi) {
                Swal.fire('Error', 'Data tidak valid', 'error');
                return;
            }

            Swal.fire({
                title: 'Edit Kondisi Mobil',
                html: `
                    <form id="editForm" method="POST" action="/unitproblem/${problem.id_kondisi}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editIdKondisi" name="editIdKondisi" value="${problem.id_kondisi}">
                        
                        <div class="mb-3 text-start">
                            <label for="editCatatanDefect" class="form-label">Catatan Defect:</label>
                            <textarea class="form-control" id="editCatatanDefect" name="catatan_defect" rows="3">${problem.catatan_defect || ''}</textarea>
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editTanggalMasukBengkel" class="form-label">Tanggal Masuk Bengkel:</label>
                            <input type="date" class="form-control" id="editTanggalMasukBengkel" name="tanggal_masuk_bengkel" value="${problem.tanggal_masuk_bengkel || ''}">
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editTanggalKeluarBengkel" class="form-label">Tanggal Keluar Bengkel:</label>
                            <input type="date" class="form-control" id="editTanggalKeluarBengkel" name="tanggal_keluar_bengkel" value="${problem.tanggal_keluar_bengkel || ''}">
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editKlaimWarranty" class="form-label">Klaim Warranty:</label>
                            <textarea class="form-control" id="editKlaimWarranty" name="klaim_warranty" rows="3">${problem.klaim_warranty || ''}</textarea>
                        </div>
                        
                        <div class="mb-3 text-start">
                            <label for="editUpdateKondisiUnit" class="form-label">Update Kondisi Unit:</label>
                            <select class="form-select" id="editUpdateKondisiUnit" name="update_kondisi_unit">
                                <option value="Maintenance" ${problem.update_kondisi_unit === 'Maintenance' ? 'selected' : ''}>Maintenance</option>
                                <option value="Sudah Diperbaiki" ${problem.update_kondisi_unit === 'Sudah Diperbaiki' ? 'selected' : ''}>Sudah Diperbaiki</option>
                            </select>
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
                preConfirm: () => {
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

        .my-swal textarea {
            min-height: 100px;
        }
    </style>
</div>
@endsection