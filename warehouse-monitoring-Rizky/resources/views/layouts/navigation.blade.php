<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="bi bi-buildings-fill text-primary me-2"></i>
            <span>Warehouse<span class="text-primary">Track</span></span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('dashboard') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2-fill me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->is('products*') ? 'active fw-semibold text-primary' : '' }}" href="/products">
                        <i class="bi bi-box-seam-fill me-1"></i> Katalog Barang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->is('locations*') ? 'active fw-semibold text-primary' : '' }}" href="/locations">
                        <i class="bi bi-geo-fill me-1"></i> Placement Barang
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="fw-medium text-dark d-none d-sm-inline-block">{{ Auth::user()->name }}</span>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2" aria-labelledby="userDropdown">
                        <li>
                            <h6 class="dropdown-header d-flex flex-column">
                                <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                                <span class="text-muted small">{{ Auth::user()->email }}</span>
                            </h6>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle me-2 text-secondary"></i> Profil Pengaturan
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar Sistem
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>