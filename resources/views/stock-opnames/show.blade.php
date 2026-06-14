@extends('layouts.sidebar')
@section('title', 'Detail Stock Opname')
@section('content')

    <div class="row">
        <div class="col-lg-12">

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-subtle text-primary rounded p-2 me-3">
                            <i class="bi bi-calculator fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ $stockOpname->opname_number }}</h5>
                            <p class="text-muted small mb-0">{{ $stockOpname->warehouse->name }} — {{ $stockOpname->opname_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div>
                        @switch($stockOpname->status)
                            @case('in_progress')
                                <span class="badge bg-info fs-6">Sedang Berjalan</span>
                                @break
                            @case('pending_adjustment')
                                <span class="badge bg-warning text-dark fs-6">Menunggu Penyesuaian</span>
                                @break
                            @case('completed')
                                <span class="badge bg-success fs-6">Selesai</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger fs-6">Dibatalkan</span>
                                @break
                        @endswitch
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <p class="text-muted small mb-1">Gudang</p>
                            <p class="fw-semibold text-dark">{{ $stockOpname->warehouse->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <p class="text-muted small mb-1">Tanggal Opname</p>
                            <p class="fw-semibold text-dark">{{ $stockOpname->opname_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <p class="text-muted small mb-1">Dibuat Oleh</p>
                            <p class="fw-semibold text-dark">{{ $stockOpname->creator->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted small mb-1">Total Detail</p>
                            <p class="fw-semibold text-dark">{{ $stockOpname->details->count() }} item</p>
                        </div>
                    </div>

                    @if($stockOpname->notes)
                        <div class="border-top pt-4">
                            <p class="text-muted small mb-1">Catatan</p>
                            <p class="text-dark">{{ $stockOpname->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Panel Persetujuan Penyesuaian Stok (Hanya untuk Admin/Manager jika status pending_adjustment) --}}
            @if($stockOpname->status === 'pending_adjustment' && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')))
                <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-left: 5px solid #ffc107 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-warning-subtle text-warning rounded p-3">
                                <i class="bi bi-shield-fill-exclamation fs-3"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold text-dark mb-1">Persetujuan Penyesuaian Stok Diperlukan</h5>
                                <p class="text-muted small mb-3">
                                    Hasil perhitungan fisik ini memiliki selisih dengan stok di sistem. Silakan tinjau perbedaan di bawah dan putuskan tindakan selanjutnya.
                                </p>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('stock-opnames.approve-adjustment', $stockOpname->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui dan menerapkan penyesuaian stok ini ke sistem?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success fw-semibold px-4 py-2">
                                            <i class="bi bi-check2-all me-1"></i> Setujui & Update Stok
                                        </button>
                                    </form>
                                    <form action="{{ route('stock-opnames.reject-adjustment', $stockOpname->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak hasil perhitungan fisik ini dan mengembalikannya ke status In Progress?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger fw-semibold px-4 py-2">
                                            <i class="bi bi-x-circle me-1"></i> Tolak & Hitung Ulang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Detail Perhitungan --}}
            @if($stockOpname->details->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                        <div class="bg-info-subtle text-info rounded p-2 me-3">
                            <i class="bi bi-list-check fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Detail Perhitungan Fisik</h5>
                            <p class="text-muted small mb-0">Perbandingan stok sistem vs stok fisik</p>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Lokasi</th>
                                        <th class="text-center">Stok Sistem</th>
                                        <th class="text-center">Stok Fisik</th>
                                        <th class="text-center">Selisih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stockOpname->details as $detail)
                                        <tr>
                                            <td>
                                                <p class="fw-semibold text-dark mb-0">{{ $detail->product->sku }}</p>
                                                <p class="text-muted small mb-0">{{ $detail->product->name }}</p>
                                            </td>
                                            <td>{{ $detail->productLocation->location->zone }}/{{ $detail->productLocation->location->rack_code }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $detail->system_qty }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $detail->physical_qty }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($detail->difference > 0)
                                                    <span class="badge bg-success">+{{ $detail->difference }}</span>
                                                @elseif($detail->difference < 0)
                                                    <span class="badge bg-danger">{{ $detail->difference }}</span>
                                                @else
                                                    <span class="badge bg-secondary">0</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center mb-4">
                    <i class="bi bi-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">Belum ada data perhitungan fisik</p>
                </div>
            @endif

            {{-- Adjustment Logs --}}
            @if($stockOpname->status === 'completed' && $stockOpname->adjustmentLogs->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                        <div class="bg-warning-subtle text-warning rounded p-2 me-3">
                            <i class="bi bi-receipt fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Log Penyesuaian Stok</h5>
                            <p class="text-muted small mb-0">Riwayat penyesuaian yang telah diterapkan</p>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Lokasi</th>
                                        <th>Jenis Penyesuaian</th>
                                        <th class="text-center">Jumlah</th>
                                        <th>Alasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stockOpname->adjustmentLogs as $log)
                                        <tr>
                                            <td>{{ $log->product->sku }} — {{ $log->product->name }}</td>
                                            <td>{{ $log->location->zone }}/{{ $log->location->rack_code }}</td>
                                            <td>
                                                @if($log->adjustment_type === 'increase')
                                                    <span class="badge bg-success">Penambahan</span>
                                                @else
                                                    <span class="badge bg-danger">Pengurangan</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $log->adjustment_qty }}</td>
                                            <td>{{ $log->reason }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('stock-opnames.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
                @if($stockOpname->status === 'in_progress')
                    <a href="{{ route('stock-opnames.edit', $stockOpname->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                @endif
            </div>

        </div>
    </div>

@endsection
