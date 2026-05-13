<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Tracking System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* Mengatur background gambar agar memenuhi layar */
        .hero-section {
            /* Kamu bisa mengganti URL ini dengan gambar gudang milikmu sendiri yang ada di folder public/images */
            background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            position: relative;
        }

        /* Overlay gelap agar teks dan search bar tetap terbaca jelas */
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.65); /* Opasitas 65% hitam */
            z-index: 1;
        }

        /* Memastikan konten berada di atas overlay */
        .content-wrapper {
            position: relative;
            z-index: 2;
        }

        /* Menghilangkan border biru saat input diklik */
        .search-input:focus {
            box-shadow: none;
        }
    </style>
</head>
<body>

    <div class="hero-section">
        <div class="hero-overlay"></div>

        <div class="content-wrapper d-flex flex-column min-vh-100">
            
            <nav class="navbar navbar-expand-lg navbar-dark bg-transparent pt-3 px-md-4">
                <div class="container">
                    <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="/">
                        <i class="bi bi-buildings-fill text-primary me-2"></i>
                        <span>Warehouse<span class="text-primary">Track</span></span>
                    </a>
                    
                    <div class="d-flex">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary rounded-pill px-4 fw-bold">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login Karyawan
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>

            <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                <div class="container text-center">
                    
                    <h1 class="text-white fw-bold mb-3 display-4">Lacak Posisi Barang Anda</h1>
                    <p class="text-white-50 mb-5 fs-5">Masukkan Order ID atau Nomor Resi untuk melihat status gudang secara real-time.</p>

                    <div class="row justify-content-center">
                        <div class="col-md-9 col-lg-7">
                            <form action="/track" method="GET" class="d-flex bg-white p-2 rounded-pill shadow-lg">
                                <span class="d-flex align-items-center ps-3 text-muted">
                                    <i class="bi bi-search fs-5"></i>
                                </span>
                                <input type="text" 
                                       name="order_id" 
                                       class="form-control search-input border-0 rounded-pill px-3 fs-5" 
                                       placeholder="Contoh: WH-884920" 
                                       required>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold fs-5 transition-all">
                                    Lacak
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <footer class="text-center text-white-50 pb-4">
                <small>&copy; {{ date('Y') }} Warehouse Monitoring System. All rights reserved.</small>
            </footer>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>