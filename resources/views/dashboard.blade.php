@extends('layouts.main')

@section('container')
<div class="container-fluid py-4">
<h2 class="mb-4 fw-bold" style="color:rgb(81, 135, 190)">Dashboard Monitoring</h2>
    <!-- Stats Cards Row -->
    <div class="row g-3 mb-5" style="min-height: 110px;">
        <!-- Mobil Card -->
        <div class="col">
            <div class="card border-0 rounded-4 bg-light bg-opacity-75" style="background-color: #f3eeff !important; height: 100%;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-secondary small">Total Mobil</p>
                            <h4 class="fw-bold mb-0">{{ array_sum($statusCounts->toArray()) }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #8e44ed;">
                            <i class="bi bi-car-front text-white fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Combined Parkir & Sudah Diperbaiki Card -->
        <div class="col">
            <div class="card border-0 rounded-4 bg-light bg-opacity-75" style="background-color: #e4f7ff !important; height: 100%;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-secondary small">Parkir dan Sudah Diperbaiki</p>
                            <h4 class="fw-bold mb-0">{{ ($statusCounts['Parkir'] ?? 0) + ($statusCounts['Sudah Diperbaiki'] ?? 0) }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #36b9ff;">
                            <i class="bi bi-p-square text-white fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dicuci Card -->
        <div class="col">
            <div class="card border-0 rounded-4 bg-light bg-opacity-75" style="background-color: #fff2e4 !important; height: 100%;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-secondary small">Sedang Dicuci</p>
                            <h4 class="fw-bold mb-0">{{ $statusCounts['Dicuci'] ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #ff9a3e;">
                            <i class="bi bi-droplet text-white fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dikeringkan Card -->
        <div class="col">
            <div class="card border-0 rounded-4 bg-light bg-opacity-75" style="background-color: #fff9e4 !important; height: 100%;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-secondary small">Sedang Dikeringkan</p>
                            <h4 class="fw-bold mb-0">{{ $statusCounts['Dikeringkan'] ?? 0 }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #ffc107;">
                            <i class="bi bi-sun text-white fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Combined Defect Card (Defect + Maintenance) -->
        <div class="col">
            <div class="card border-0 rounded-4 bg-light bg-opacity-75" style="background-color: #ffe4e4 !important; height: 100%;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-secondary small">Defect dan Maintenance</p>
                            <h4 class="fw-bold mb-0">{{ ($statusCounts['Defect'] ?? 0) + ($statusCounts['Maintenance'] ?? 0) }}</h4>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #dc3545;">
                            <i class="bi bi-exclamation-triangle text-white fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sudah Diperbaiki Card sudah digabung dengan Parkir -->

    </div>
    <!-- Charts Row -->
    <div class="row g-4">
    <!-- Bar Chart -->
    <div class="col-md-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-0">Visualisasi Status Mobil</h5>
                        <p class="text-secondary small mb-0">Perbandingan tiap status</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="d-flex align-items-center me-3">
                            <span class="bg-purple rounded-circle me-2" style="width: 10px; height: 10px; background-color: #8e44ed;"></span>
                            <small class="text-secondary">Parkir</small>
                        </span>
                        <span class="d-flex align-items-center me-3">
                            <span class="bg-orange rounded-circle me-2" style="width: 10px; height: 10px; background-color: #ff9a3e;"></span>
                            <small class="text-secondary">Dicuci</small>
                        </span>
                        <span class="d-flex align-items-center">
                            <span class="bg-blue rounded-circle me-2" style="width: 10px; height: 10px; background-color: #4a6fdc;"></span>
                            <small class="text-secondary">Maintenance</small>
                        </span>
                    </div>
                </div>
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut Chart -->
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Status Mobil</h5>
                <div class="position-relative" style="height: 250px;">
                    <canvas id="donutChart"></canvas>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <h6 class="text-secondary small mb-0">Total</h6>
                        <h3 class="fw-bold mb-0">{{ array_sum($statusCounts->toArray()) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Script JavaScript dijalankan'); // Log untuk memastikan script dijalankan

        // Data dari controller
        const statusLabels = {!! json_encode($statusLabels) !!};
        const statusValues = {!! json_encode($statusValues) !!};

        console.log('Status Labels:', statusLabels);
        console.log('Status Values:', statusValues);

        // Render Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Jumlah Mobil',
                    data: statusValues,
                    backgroundColor: [
                        '#8e44ed', 
                        '#ffc107', 
                        '#36b9ff', 
                        '#ff9a3e', 
                        '#dc3545', 
                        '#4a6fdc'  
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true, // Ubah ke true
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Render Donut Chart
        const donutCtx = document.getElementById('donutChart').getContext('2d');
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                       '#8e44ed', 
                        '#ffc107', 
                        '#36b9ff', 
                        '#ff9a3e', 
                        '#dc3545', 
                        '#4a6fdc' 
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    body {
        font-family: 'Poppins', 'Helvetica', 'Arial', sans-serif;
        background-color: #f9fafc;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    
    h2, h3, h4, h5, h6 {
        color: #333;
    }
    
    .text-secondary {
        color: #6c757d !important;
    }
    
    /* Adjustments for uniform card heights */
    .col {
        display: flex;
    }
    
    .card {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 250px; /* Atur tinggi tetap */
        overflow-y: auto; /* Tambahkan scroll jika konten melebihi tinggi */
    }
    
    /* Ensure text doesn't wrap awkwardly in status labels */
    .card p.small {
        font-size: 0.75rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    canvas {
        display: block;
        max-width: 100%; /* Batasi lebar */
        max-height: 100px; /* Batasi tinggi */
        height: auto;
    }
</style>
@endsection