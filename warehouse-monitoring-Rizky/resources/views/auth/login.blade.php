<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Tracking System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
    /* Mengatur background sisi kiri */
    .hero-section {
        background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
    }

    /* Modifikasi Input Group agar ikon dan input menyatu tanpa garis pemisah */
    .custom-input-group .form-control:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }
    .custom-input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(59, 159, 255, 0.25);
        border-radius: 0.375rem;
    }
    .custom-input-group .input-group-text, 
    .custom-input-group .form-control {
        border-color: #e5e7eb;
    }
</style>
</head>
<body>

    <div class="container-fluid p-0">
    <div class="row g-0 vh-100">
        
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center position-relative text-center text-white hero-section">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark" style="opacity: 0.65; z-index: 1;"></div>
            
            <div class="position-relative px-5" style="z-index: 2;">
                <h1 class="display-4 fw-bold mb-3">WarehouseTrack</h1>
                <p class="fs-5 fw-light">Pantau pergerakan logistik dan kelola inventaris gudang Anda secara real-time dalam satu platform terpadu.</p>
            </div>
        </div>

        <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-white p-4">
            
            <div class="w-100" style="max-width: 420px;">
                <div class="mb-5 d-flex align-items-center">
                    <i class="bi bi-buildings-fill fs-3 text-primary me-2"></i>
                    <span class="fs-4 fw-bold text-dark">WarehouseTrack</span>
                </div>

                <h2 class="fw-bold mb-1 text-dark">Selamat Datang</h2>
                <p class="text-muted mb-5">Silakan masuk ke akun Anda</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold text-secondary small">Email</label>
                        <div class="input-group custom-input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="email" id="email" name="email" class="form-control border-start-0 ps-0 py-2" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold text-secondary small">Kata Sandi</label>
                        <div class="input-group custom-input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" id="password" name="password" class="form-control border-start-0 border-end-0 ps-0 py-2" placeholder="Kata sandi" required>
                            <span class="input-group-text bg-white border-start-0 text-muted pe-3" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash" id="eyeIcon"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-4 fw-bold rounded-3">
                        Login
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function (e) {
            // Toggle type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle icon
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>