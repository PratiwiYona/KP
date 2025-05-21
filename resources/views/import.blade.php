@extends('layouts.main')
@section('container')
<div class="container-fluid py-4">
    <div class="row mb-5">
        <div>
            <h2 class="fw-bold" style="color:rgb(81, 135, 190)">Import Data</h2>
            <p class="text-muted">Upload file untuk import data unit kendaraan</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <!-- Import Unit Masuk -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-lg" style="border-radius: 8px;">
                <div class="card-header py-3 text-center" style="background-color: rgb(131, 184, 236);">
                    <h4 class="mb-0 text-center" style="color: #fff;">Import Unit Masuk</h4>
                </div>
                <div class="card-body p-4">
                    <p class="card-text text-muted mb-4">Upload file Excel untuk mengimport data unit masuk</p>
                    
                    <form action="{{ route('mobil.import') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="unitMasukFile" class="form-label">Pilih File</label>
                            <input class="form-control" type="file" id="unitMasukFile" name="file" required>
                            <div class="form-text">Format yang didukung: .xlsx, .xls, .csv</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cloud-upload me-2"></i>Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Import Unit Keluar -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-lg" style="border-radius: 8px;">
                <div class="card-header py-3 text-center" style="background-color: rgb(131, 184, 236);">
                    <h4 class="mb-0 text-center" style="color: #fff;">Import Unit Keluar</h4>
                </div>
                <div class="card-body p-4">
                    <p class="card-text text-muted mb-4">Upload file Excel untuk mengimport data unit keluar</p>
                    
                    <form action="{{ route('mobil.importUnitKeluar') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="unitKeluarFile" class="form-label">Pilih File</label>
                            <input class="form-control" type="file" id="unitKeluarFile" name="file" required>
                            <div class="form-text">Format yang didukung: .xlsx, .xls, .csv</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cloud-upload me-2"></i>Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection