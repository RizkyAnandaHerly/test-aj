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
                
                @if(!$salesOrder)
                    <form action="/track" method="GET" class="mb-5">
                        <div class="input-group input-group-lg border rounded-pill overflow-hidden bg-white shadow-sm">
                            <span class="input-group-text bg-transparent border-0 ps-4">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Masukkan Nomor Sales Order..." value="{{ $orderId }}" style="box-shadow: none;" required>
                            <button class="btn btn-primary px-5 fw-bold" type="submit">Lacak Baru</button>
                        </div>
                    </form>
                @endif

                @if($search && (($type === 'so' && !$salesOrder) || ($type === 'packing' && !$packingDetail)))
                    <div class="alert alert-danger rounded-4 p-4 text-center shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill fs-1 text-danger mb-3 d-block"></i>
                        <h4 class="fw-bold">Data Tidak Ditemukan</h4>
                        <p class="mb-0">Maaf, kami tidak dapat menemukan data pelacakan untuk nomor/label <strong>{{ $search }}</strong>. Pastikan nomor yang Anda masukkan benar.</p>
                    </div>
                @elseif($type === 'so' && $salesOrder)
                    {{-- ── Tampilan Pelacakan Sales Order (SO) ── --}}
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
                        <div class="card-header bg-primary text-white p-4 border-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <p class="text-white-50 text-uppercase small fw-bold mb-1">Nomor Pelacakan SO</p>
                                    <h4 class="mb-0 fw-bold">{{ $salesOrder->order_number }}</h4>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill fs-6 mb-1 shadow-sm">
                                        <i class="bi bi-box-seam me-2"></i>{{ $salesOrder->status }}
                                    </span>
                                    <p class="mb-0 text-white-50 small">Pelanggan: {{ $salesOrder->customer_name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-4 p-md-5 bg-white">
                            <div class="row mb-5 pb-4 border-bottom g-4">
                                <div class="col-md-4">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Rute Pengiriman</p>
                                    <h6 class="fw-bold">
                                        {{ $salesOrder->origin_country }} <i class="bi bi-arrow-right mx-1 text-primary"></i> {{ $salesOrder->destination_country }}
                                    </h6>
                                </div>
                                <div class="col-md-4 border-start-md ps-md-4">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Total Nilai Pesanan</p>
                                    <h6 class="fw-bold">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</h6>
                                </div>
                                <div class="col-md-4 border-start-md ps-md-4">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Deskripsi/Keterangan</p>
                                    <h6 class="fw-bold text-primary">{{ $salesOrder->description ?? 'Tidak ada catatan.' }}</h6>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-4">Riwayat Status Pesanan</h5>
                            
                            <div class="position-relative">
                                <div class="timeline-line"></div>
                                
                                <div class="timeline-item">
                                    <div class="position-absolute" style="left:0; top:0;">
                                        <div class="timeline-node active"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="fw-bold text-primary mb-1">Status Saat Ini: {{ $salesOrder->status }}</h6>
                                        <small class="text-muted fw-medium">{{ $salesOrder->updated_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="text-muted mb-0">Pesanan saat ini berada pada tahap {{ strtolower($salesOrder->status) }}.</p>
                                </div>

                                <div class="timeline-item">
                                    <div class="position-absolute" style="left:0; top:0;">
                                        <div class="timeline-node"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="fw-bold text-dark mb-1">Pesanan Dibuat</h6>
                                        <small class="text-muted fw-medium">{{ $salesOrder->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="text-muted mb-0">Sales Order telah berhasil dibuat dan direkam di dalam sistem.</p>
                                </div>
                            </div>

                            <div class="text-center mt-5 pt-3 border-top">
                                <a href="/track" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="bi bi-search me-2"></i>Lacak Nomor Lainnya
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif($type === 'packing' && $packingDetail)
                    {{-- ── Tampilan Ketertelusuran (Traceability) Label Packing ── --}}
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
                        <div class="card-header bg-dark text-white p-4 border-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <p class="text-white-50 text-uppercase small fw-bold mb-1">Traceability Label</p>
                                    <h4 class="mb-0 fw-bold">{{ $packingDetail->label_code }}</h4>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary px-3 py-2 rounded-pill fs-6 mb-1 shadow-sm">
                                        <i class="bi bi-qr-code me-2"></i>Packed & Labeled
                                    </span>
                                    <p class="mb-0 text-white-50 small">Barang: {{ $packingDetail->product->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-4 p-md-5 bg-white">
                            <div class="row mb-5 pb-4 border-bottom g-4">
                                <div class="col-md-4">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Spesifikasi Barang</p>
                                    <h6 class="fw-bold text-dark mb-1">{{ $packingDetail->product->name }}</h6>
                                    <span class="text-secondary small">SKU: {{ $packingDetail->product->sku }}</span>
                                </div>
                                <div class="col-md-4 border-start-md ps-md-4">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Detail Fisik Kemasan</p>
                                    <h6 class="fw-bold mb-1">{{ $packingDetail->packaging_type }} ({{ number_format($packingDetail->quantity) }} {{ $packingDetail->product->unit ?? 'pcs' }})</h6>
                                    <span class="text-secondary small">Dimensi: {{ $packingDetail->package_dimensions ?? '-' }} | Berat: {{ $packingDetail->package_weight ?? '-' }}</span>
                                </div>
                                <div class="col-md-4 border-start-md ps-md-4">
                                    <p class="text-muted small fw-bold text-uppercase mb-1">Catatan</p>
                                    <p class="small text-secondary mb-0">{{ $packingDetail->notes ?? 'Tidak ada catatan fisik.' }}</p>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-4"><i class="bi bi-clock-history me-2 text-primary"></i>Alur Ketertelusuran Rantai Pasok</h5>
                            
                            <div class="position-relative">
                                <div class="timeline-line" style="left: 11px;"></div>
                                
                                {{-- 1. Pengepakan (Packing) --}}
                                <div class="timeline-item">
                                    <div class="position-absolute" style="left:0; top:0;">
                                        <div class="timeline-node active"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="fw-bold text-primary mb-1">Tahap 4: Pengepakan & Pelabelan Selesai</h6>
                                        <small class="text-muted fw-semibold">{{ $packingDetail->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="text-muted mb-1">Barang telah dikemas menggunakan tipe kemasan <strong>{{ $packingDetail->packaging_type }}</strong> sebanyak <strong>{{ number_format($packingDetail->quantity) }}</strong> unit dan ditempeli label barcode/QR Code oleh packer <strong>{{ $packingDetail->packer->name ?? '-' }}</strong>.</p>
                                </div>

                                {{-- 2. Penyimpanan Lokasi --}}
                                <div class="timeline-item">
                                    <div class="position-absolute" style="left:0; top:0;">
                                        <div class="timeline-node"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="fw-bold text-dark mb-1">Tahap 3: Penyimpanan Gudang (Putaway)</h6>
                                        <small class="text-muted fw-semibold">{{ $packingDetail->inbound->created_at->format('d M Y') }}</small>
                                    </div>
                                    <p class="text-muted mb-1">Barang disimpan di 
                                        <strong>
                                            @if($packingDetail->product->productLocations->first()?->location)
                                                Gudang {{ $packingDetail->product->productLocations->first()?->location->warehouse->name ?? '-' }} · Zona {{ $packingDetail->product->productLocations->first()?->location->zone ?? '-' }} · Rak {{ $packingDetail->product->productLocations->first()?->location->rack_code ?? '-' }}
                                            @else
                                                Lokasi penyimpanan belum ditentukan
                                            @endif
                                        </strong>.
                                    </p>
                                </div>

                                {{-- 3. Inspeksi QC --}}
                                <div class="timeline-item">
                                    <div class="position-absolute" style="left:0; top:0;">
                                        <div class="timeline-node"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="fw-bold text-dark mb-1">Tahap 2: Quality Control (QC Inspection)</h6>
                                        <small class="text-muted fw-semibold">
                                            {{ $packingDetail->inbound->qcInspection ? $packingDetail->inbound->qcInspection->created_at->format('d M Y') : '—' }}
                                        </small>
                                    </div>
                                    @if($packingDetail->inbound->qcInspection)
                                        <p class="text-muted mb-1">
                                            Status Inspeksi: 
                                            @if($packingDetail->inbound->qcInspection->status === 'pass')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">QC PASS</span>
                                            @elseif($packingDetail->inbound->qcInspection->status === 'fail')
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">QC REJECT</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">{{ strtoupper($packingDetail->inbound->qcInspection->status) }}</span>
                                            @endif
                                            oleh Inspector <strong>{{ $packingDetail->inbound->qcInspection->inspector->name ?? '-' }}</strong>.
                                        </p>
                                        <p class="text-muted small mb-1">Catatan QC: <em>"{{ $packingDetail->inbound->qcInspection->notes ?? 'Tidak ada catatan.' }}"</em></p>
                                    @else
                                        <p class="text-muted mb-1"><span class="badge bg-secondary-subtle text-secondary rounded-pill">QC PENDING</span>. Barang masuk belum melewati proses inspeksi mutu.</p>
                                    @endif
                                </div>

                                {{-- 4. Inbound --}}
                                <div class="timeline-item">
                                    <div class="position-absolute" style="left:0; top:0;">
                                        <div class="timeline-node"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="fw-bold text-dark mb-1">Tahap 1: Penerimaan Barang Masuk (Inbound)</h6>
                                        <small class="text-muted fw-semibold">{{ $packingDetail->inbound->received_date->format('d M Y') }}</small>
                                    </div>
                                    <p class="text-muted mb-1">Diterima dari vendor <strong>{{ $packingDetail->inbound->vendor->name ?? '-' }}</strong> (Kode Vendor: {{ $packingDetail->inbound->vendor->code ?? '-' }}) dengan nomor batch <strong>{{ $packingDetail->inbound->batch_no ?? '-' }}</strong>.</p>
                                </div>
                            </div>

                            {{-- Sertifikat Kualitas --}}
                            <div class="mt-5 pt-4 border-top">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-check text-success me-2"></i>Dokumen Sertifikasi Produk</h6>
                                @if($packingDetail->product->certifications && $packingDetail->product->certifications->count() > 0)
                                    <div class="row g-3">
                                        @foreach($packingDetail->product->certifications as $cert)
                                            <div class="col-md-6">
                                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <div class="fw-bold text-dark small">{{ $cert->certification_type }}</div>
                                                        <div class="text-muted small" style="font-size: 0.75rem;">Lot: {{ $cert->lot_number }} | Standar: {{ $cert->standard_region }}</div>
                                                    </div>
                                                    <a href="{{ Storage::url($cert->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                        <i class="bi bi-eye"></i> Lihat
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-muted small italic p-3 bg-light rounded-3">
                                        <i class="bi bi-info-circle me-1"></i> Tidak ada dokumen sertifikat kualitas yang diunggah untuk barang ini.
                                    </div>
                                @endif
                            </div>

                            <div class="text-center mt-5 pt-3 border-top">
                                <a href="/track" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="bi bi-search me-2"></i>Lacak Nomor Lainnya
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-5 mt-4">
                        <i class="bi bi-search display-1 opacity-25 mb-3 d-block"></i>
                        <h5>Sistem Pelacakan Siap</h5>
                        <p>Masukkan Nomor Sales Order (SO) Anda untuk melihat detail pengiriman.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

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