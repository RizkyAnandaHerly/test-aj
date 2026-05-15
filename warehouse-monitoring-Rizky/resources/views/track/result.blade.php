<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pelacakan - WarehouseTrack</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .timeline-line {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 11px;
            width: 2px;
            background-color: #dee2e6;
            z-index: 0;
        }
        .timeline-node {
            position: relative;
            z-index: 1;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #fff;
            border: 4px solid #dee2e6;
            margin-left: 0;
        }
        .timeline-node.active {
            border-color: #0d6efd;
            background-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
        }
        .timeline-item { position: relative; padding-left: 40px; margin-bottom: 30px; }
    </style>
</head>
<body>
    <!-- Navbar Minimalis -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center text-dark" href="/">
                <i class="bi bi-buildings-fill text-primary me-2"></i>
                <span>Warehouse<span class="text-primary">Track</span></span>
            </a>
            <div class="d-flex ms-auto">
                <a href="/" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <form action="/track" method="GET" class="mb-5">
                    <div class="input-group input-group-lg border rounded-pill overflow-hidden bg-white shadow-sm">
                        <span class="input-group-text bg-transparent border-0 ps-4">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="order_id" class="form-control border-0 bg-transparent" placeholder="Masukkan Batch No / Order ID..." value="{{ $orderId }}" style="box-shadow: none;" required>
                        <button class="btn btn-primary px-5 fw-bold" type="submit">Lacak Baru</button>
                    </div>
                </form>

                @if($orderId && !$inbound)
                    <div class="alert alert-danger rounded-4 p-4 text-center shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill fs-1 text-danger mb-3 d-block"></i>
                        <h4 class="fw-bold">Data Tidak Ditemukan</h4>
                        <p class="mb-0">Maaf, kami tidak dapat menemukan data pengiriman untuk nomor resi/batch <strong>{{ $orderId }}</strong>. Pastikan nomor yang Anda masukkan benar.</p>
                    </div>
                @elseif($inbound)
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
                        <div class="card-header bg-primary text-white p-4 border-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <p class="text-white-50 text-uppercase small fw-bold mb-1">Nomor Pelacakan</p>
                                    <h4 class="mb-0 fw-bold">{{ $inbound->batch_no }}</h4>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill fs-6 mb-1 shadow-sm">
                                        <i class="bi bi-box-seam me-2"></i>{{ $inbound->qcInspection ? 'Selesai QC' : 'Baru Diterima' }}
                                    </span>
                                    <p class="mb-0 text-white-50 small">Pemasok: {{ $inbound->vendor->name ?? 'Internal' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-4 p-md-5 bg-white">
                            <!-- Informasi Produk -->
                            <div class="row mb-5 pb-4 border-bottom g-4">
                                <div class="col-md-4">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Nama Produk (Kopi)</p>
                                    <h6 class="fw-bold">{{ $inbound->product->name }}</h6>
                                </div>
                                <div class="col-md-4 border-start-md ps-md-4">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">SKU</p>
                                    <h6 class="fw-bold">{{ $inbound->product->sku }}</h6>
                                </div>
                                <div class="col-md-4 border-start-md ps-md-4">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Kuantitas Masuk</p>
                                    <h6 class="fw-bold text-primary">{{ number_format($inbound->qty, 0, ',', '.') }} {{ $inbound->product->unit }}</h6>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-4">Riwayat Status Gudang</h5>
                            
                            <div class="position-relative">
                                <div class="timeline-line"></div>
                                
                                {{-- Jika ada QC --}}
                                @if($inbound->qcInspection)
                                    <div class="timeline-item">
                                        <div class="position-absolute" style="left:0; top:0;">
                                            <div class="timeline-node active"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <h6 class="fw-bold text-primary mb-1">Quality Control Selesai ({{ ucfirst($inbound->qcInspection->status) }})</h6>
                                            <small class="text-muted fw-medium">{{ $inbound->qcInspection->created_at->format('d M Y, H:i') }}</small>
                                        </div>
                                        <p class="text-muted mb-0">Barang telah melalui inspeksi kualitas oleh {{ $inbound->qcInspection->inspector->name ?? 'Inspektur' }}. Catatan: {{ $inbound->qcInspection->notes ?? '-' }}</p>
                                    </div>
                                @endif

                                {{-- Selalu Tampilkan Inbound --}}
                                <div class="timeline-item">
                                    <div class="position-absolute" style="left:0; top:0;">
                                        <div class="timeline-node {{ !$inbound->qcInspection ? 'active' : '' }}"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="fw-bold {{ !$inbound->qcInspection ? 'text-primary' : 'text-dark' }} mb-1">Barang Diterima di Gudang</h6>
                                        <small class="text-muted fw-medium">{{ $inbound->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="text-muted mb-0">Barang telah tiba di fasilitas gudang dan diterima oleh staf logistik ({{ $inbound->receiver->name ?? 'Staf' }}).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Tampilan Awal jika belum ada pencarian -->
                    <div class="text-center text-muted py-5 mt-4">
                        <i class="bi bi-box-seam display-1 opacity-25 mb-3 d-block"></i>
                        <h5>Sistem Pelacakan Siap</h5>
                        <p>Masukkan Nomor Batch Inbound Anda untuk melihat detail informasi produk.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer Minimalis -->
    <footer class="bg-dark text-white pt-5 pb-3 mt-auto">
        <div class="container text-center">
            <h5 class="fw-bold mb-3"><i class="bi bi-buildings-fill text-primary me-2"></i>WarehouseTrack</h5>
            <p class="text-secondary small mb-4">Mendukung Transparansi Penuh pada Rantai Pasok Anda.</p>
            <div class="text-secondary small">
                &copy; {{ date('Y') }} Warehouse Monitoring System. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
