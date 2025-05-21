<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/layout1.css') }}">
</head>
<body id="body-pd">
    
    <header class="header" id="header">
        <div class="header_toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
        <div class="">
            <p>
                {{ Auth::user()->username }}
            </p>
        </div>
    </header>

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo">
                    <i class="bi bi-car-front"></i>
                    <span class="nav_logo-name">STORAGE SUI AMBAWANG</span>
                </a>

                <div class="nav_list">
                    <a href="{{ route('dashboard') }}" class="nav_link {{ request() -> is('dashboard') ? ' active ' : '' }}">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name">Dashboard</span>
                    </a>
                    <a href="{{ route('stokmanual') }}" class="nav_link {{ request()->is('stokmanual') ? ' active ' : '' }}">
                        <i class="bi bi-table"></i>
                        <span class="nav_name">Stok Manual</span>
                    </a>
                    <a href="{{ route('unitmasuk') }}" class="nav_link {{ request()->is('unitmasuk') ? ' active ' : '' }}">
                        <i class='bx bx-message-square-detail nav_icon'></i>
                        <span class="nav_name">Unit Masuk</span>
                    </a>
                    <a href="{{ route('unitproblem') }}" class="nav_link {{ request()->is('unitproblem') ? ' active ' : '' }}">
                        <i class="bi bi-file-earmark-x fs-5"></i>
                        <span class="nav_name">Unit Problem</span>
                    </a>
                    <a href="{{ route('mobil.form') }}" class="nav_link {{ request()->is('import-mobil*') ? ' active ' : '' }}"> <!--{{ request()->is('import-mobil*') ? ' active ' : '' }}-->
                        <i class="bi bi-cloud-arrow-up fs-5"></i>
                        <span class="nav_name">Import</span>
                    </a>
                    @if (Auth::user()->role == 'admin')
                    <a href="{{ route('adduser') }}" class="nav_link {{ request()->is('adduser') ? ' active ' : '' }}">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name">Add User</span>
                    </a>
                    @endif
                </div>
            </div>
            <a href="#" class="nav_link logout_link" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class='bx bx-log-out nav_icon'></i>
                <span class="nav_name">Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>


        </nav>
    </div>

    <!--Container Main start-->
    <div class="container">
        <div class="row justify-content-center mt-4 mb-4">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="row justify-content-center mt-4 mb-4">
            <div class="col-md-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class="row justify-content-center mt-4 mb-4">
            <div class="col-md-8">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
        
        @yield('container')
        
    </div>
    <!--Container Main end-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/layout1.js') }}"></script>

    @yield('scripts') 
</body>
</html>
