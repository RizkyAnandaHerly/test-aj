<x-app-layout>
    <x-slot name="header">Master Gudang</x-slot>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
            <span class="fw-semibold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-dark">Daftar Gudang</h5>
                <p class="text-muted small mb-0">Kelola data gedung gudang yang tersedia</p>
            </div>
            <a href="{{ route('warehouses.create') }}" class="btn btn-primary fw-bold shadow-sm px-4">
                <i class="bi bi-plus-circle me-2"></i> Tambah Gudang
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">#</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Nama Gudang</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Kode</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">PIC</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Telepon</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Status</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-secondary text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warehouses as $wh)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $wh->name }}</div>
                                    <div class="text-muted small">{{ $wh->address }}</div>
                                </td>
                                <td><span class="badge bg-secondary rounded-pill">{{ $wh->code }}</span></td>
                                <td class="text-muted">{{ $wh->pic_name ?? '—' }}</td>
                                <td class="text-muted">{{ $wh->phone ?? '—' }}</td>
                                <td class="text-center">
                                    @if($wh->status === 'active')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Aktif</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('warehouses.edit', $wh) }}" class="btn btn-sm btn-light border shadow-sm">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-building fs-2 d-block mb-2"></i>
                                    Belum ada data gudang. <a href="{{ route('warehouses.create') }}">Tambah sekarang</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
