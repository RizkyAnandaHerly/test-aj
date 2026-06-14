<?php $__env->startSection('title', 'Penempatan Lokasi'); ?>
<?php $__env->startSection('content'); ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
            <span class="fw-semibold"><?php echo e(session('success')); ?></span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-dark">Data Placement Gudang</h5>
                <p class="text-muted small mb-0">Daftar barang yang sudah dialokasikan ke lokasi rak</p>
            </div>
            <a href="<?php echo e(route('locations.create')); ?>" class="btn btn-primary fw-bold shadow-sm px-4">
                <i class="bi bi-plus-circle me-2"></i> Alokasi Baru
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">Barang</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary">Gudang</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Lokasi (Zona)</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Rak / Palet</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Lantai</th>
                            <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Jumlah Simpan</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-secondary text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $placements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?php echo e($item->product->sku); ?></div>
                                    <div class="text-muted small"><?php echo e($item->product->name); ?></div>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark"><?php echo e($item->location->warehouse->name ?? '—'); ?></div>
                                    <div class="text-muted small"><?php echo e($item->location->warehouse->code ?? ''); ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3">
                                        Zona <?php echo e($item->location->zone); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="fw-medium text-dark"><?php echo e($item->location->rack_code); ?></div>
                                    <?php if($item->location->pallet_code): ?>
                                        <div class="text-muted small">Palet: <?php echo e($item->location->pallet_code); ?></div>
                                    <?php else: ?>
                                        <div class="text-muted small">—</div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary rounded-pill">Lvl <?php echo e($item->location->floor_level); ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary"><?php echo e($item->qty_stored); ?></span>
                                    <span class="text-muted small"><?php echo e($item->product->unit); ?></span>
                                </td>
                                <td class="pe-4 text-end">
                                    
                                    <form action="<?php echo e(route('locations.placement.destroy', $item)); ?>"
                                          method="POST"
                                          onsubmit="return confirm('Hapus penempatan ini?')"
                                          style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-light border text-danger shadow-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-geo-alt fs-2 d-block mb-2"></i>
                                    Belum ada data penempatan barang.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rakea\Documents\GitHub\test-aj\resources\views/locations/index.blade.php ENDPATH**/ ?>