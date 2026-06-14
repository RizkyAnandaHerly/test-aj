@extends('layouts.sidebar')

@section('title', 'Dashboard Requester')

@section('content')

{{-- ── Welcome ───────────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0 text-dark">
            Halo, {{ Auth::user()->name }}! 👋
        </h4>
        <p class="text-muted mb-0 small">
            Pantau status pesanan Anda di sini · {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>
    <span class="badge px-3 py-2 rounded-pill"
          style="background:#f3e8ff;color:#6b21a8;font-size:.75rem;font-weight:600;">
        <i class="bi bi-person me-1"></i> Requester
    </span>
</div>

{{-- ── Welcome ───────────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0 text-dark">
            Halo, {{ Auth::user()->name }}! 👋
        </h4>
        <p class="text-muted mb-0 small">
            Pantau status pesanan dan ketertelusuran barang Anda di sini · {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>
    <span class="badge px-3 py-2 rounded-pill"
          style="background:#f3e8ff;color:#6b21a8;font-size:.75rem;font-weight:600;">
        <i class="bi bi-person me-1"></i> Requester
    </span>
</div>

{{-- ── Search & Scan Dashboard Panel ────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 p-md-5 text-center bg-white">
        <div class="mx-auto" style="max-width: 600px;">
            <div class="mb-4">
                <span class="fs-1">🔍</span>
                <h5 class="fw-bold text-dark mt-2 mb-2">Pelacakan Rantai Pasok & Pesanan</h5>
                <p class="text-muted small">Masukkan Nomor Sales Order (SO) atau pindai QR Code pada Label Packing (PKG-) untuk melacak data ketertelusuran barang.</p>
            </div>
            
            <form action="{{ route('track') }}" method="GET" class="mb-3">
                <div class="input-group input-group-lg border rounded-pill overflow-hidden bg-light shadow-sm">
                    <span class="input-group-text bg-transparent border-0 ps-3">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-0 bg-transparent py-3" placeholder="Contoh: SO-2026-001 atau PKG-..." style="box-shadow: none; font-size: 0.95rem;" required>
                    <button class="btn btn-primary px-4 fw-bold rounded-pill-end" type="submit">Cari</button>
                </div>
            </form>

            <div class="d-flex justify-content-center align-items-center gap-2 mt-4">
                <button type="button" class="btn btn-dark rounded-pill px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#qrScanModal">
                    <i class="bi bi-qr-code-scan me-2"></i> Scan QR Code Label
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── Traceability & Status Flow Explanations ─────────────────────────── --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-qr-code text-primary me-2"></i>Bagaimana Cara Melacak Label?
                </h6>
            </div>
            <div class="card-body p-4">
                <ol class="text-muted small ps-3 mb-0" style="line-height: 1.6;">
                    <li class="mb-2">Klik tombol <strong>"Scan QR Code Label"</strong> di atas.</li>
                    <li class="mb-2">Berikan izin akses kamera jika browser memintanya.</li>
                    <li class="mb-2">Arahkan kamera ke QR Code yang tertempel pada label packing fisik barang.</li>
                    <li>Sistem akan otomatis mengalihkan Anda ke halaman detail ketertelusuran (Vendor, Status QC, Lokasi Rak, Packer).</li>
                </ol>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-shield-check text-success me-2"></i>Jaminan Transparansi Data
                </h6>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3" style="line-height: 1.6;">
                    WMS kami merekam setiap pergerakan barang mulai dari penerimaan di pintu masuk gudang (Inbound) oleh vendor, pemeriksaan kualitas (QC), penyimpanan di lokasi yang tepat, hingga pengepakan.
                </p>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Vendor Verified</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">QC Inspected</span>
                    <span class="badge bg-info-subtle text-info border border-info-subtle">Fully Traceable</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Modal Scan QR Code ─────────────────────────────────────────────────── --}}
<div class="modal fade" id="qrScanModal" tabindex="-1" aria-labelledby="qrScanModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="qrScanModalLabel">
                    <i class="bi bi-qr-code-scan me-2 text-primary"></i>Pindai QR Code Label
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close-scanner"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="text-muted small mb-3">Posisikan QR Code berada di dalam kotak kamera.</p>
                
                {{-- Container HTML5 QR Code --}}
                <div class="mx-auto rounded-3 overflow-hidden border bg-light" id="reader" style="width: 100%; max-width: 350px; min-height: 250px;"></div>
                
                <div class="text-danger small mt-2 d-none" id="scanner-error"></div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal" id="btn-cancel-scanner">Batal</button>
            </div>
        </div>
    </div>
</div>

{{-- Script HTML5 QR Code --}}
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRV0VU7CctvAZOIPBfGD911N3XIvDXZKLD3jUBfyHptQySjdCnddKLw+tW1nRRUR31cLA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let html5QrcodeScanner = null;
        const modalElement = document.getElementById('qrScanModal');
        const readerElement = document.getElementById('reader');
        const errorElement = document.getElementById('scanner-error');
        const btnCancel = document.getElementById('btn-cancel-scanner');
        const btnClose = document.getElementById('btn-close-scanner');
        
        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanner
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().then(() => {
                    // Redirect to tracking page
                    window.location.href = `/track?search=${encodeURIComponent(decodedText)}`;
                }).catch(err => {
                    console.error("Gagal menghentikan scanner: ", err);
                    window.location.href = `/track?search=${encodeURIComponent(decodedText)}`;
                });
            }
        }
        
        function onScanFailure(error) {
            // Kegagalan scanning berkala (misal belum fokus), abaikan agar tidak spamming console
        }
        
        // Mulai kamera saat modal terbuka
        modalElement.addEventListener('shown.bs.modal', function () {
            errorElement.classList.add('d-none');
            
            // Inisialisasi scanner
            html5QrcodeScanner = new Html5Qrcode("reader");
            
            html5QrcodeScanner.start(
                { facingMode: "environment" }, // Prioritaskan kamera belakang
                {
                    fps: 10,
                    qrbox: { width: 200, height: 200 }
                },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Kamera gagal diakses: ", err);
                errorElement.textContent = "Kamera gagal diakses. Pastikan Anda mengizinkan akses kamera.";
                errorElement.classList.remove('d-none');
            });
        });
        
        // Hentikan kamera saat modal tertutup
        function stopScanner() {
            if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner = null;
                }).catch(err => {
                    console.error("Gagal menghentikan kamera saat modal tutup: ", err);
                });
            }
        }
        
        modalElement.addEventListener('hidden.bs.modal', stopScanner);
        btnCancel.addEventListener('click', stopScanner);
        btnClose.addEventListener('click', stopScanner);
    });
</script>
@endsection

{{-- ── Contact Info ─────────────────────────────────────────────────────── --}}
<div class="card border-0 rounded-3" style="background:linear-gradient(135deg,#1e293b,#334155);">
    <div class="card-body p-4 d-flex align-items-center gap-4 flex-wrap">
        <div style="font-size:2rem;flex-shrink:0;">📞</div>
        <div class="flex-1">
            <div class="fw-bold text-white mb-1">Butuh bantuan?</div>
            <div class="text-muted" style="font-size:.82rem;color:#94a3b8!important;">
                Hubungi tim gudang kami untuk menanyakan status pesanan Anda secara langsung.
            </div>
        </div>
    </div>
</div>

@endsection
