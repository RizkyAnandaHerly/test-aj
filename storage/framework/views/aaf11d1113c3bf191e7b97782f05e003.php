<?php $__env->startSection('title', 'Daftar Sertifikasi'); ?>
<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        <div class="col-lg-11">

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Daftar Sertifikasi</h4>
                    <p class="text-muted small mb-0">Riwayat dokumen sertifikasi dan data traceability lot kopi.</p>
                </div>
                <a href="<?php echo e(route('certifications.create')); ?>" class="btn btn-warning fw-semibold px-4">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Sertifikasi
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Lot</th>
                                    <th>Standar</th>
                                    <th>Status</th>
                                    <th>Certifier</th>
                                    <th>Tanggal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $certifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($certification->id); ?></td>
                                        <td>
                                            <?php echo e($certification->product->name ?? '—'); ?><br>
                                            <small class="text-muted"><?php echo e($certification->product->sku ?? '—'); ?></small>
                                        </td>
                                        <td><?php echo e($certification->lot_number); ?></td>
                                        <td><?php echo e($certification->standard_region); ?></td>
                                        <td class="text-capitalize">
                                            <?php if($certification->status === 'valid'): ?>
                                                <span class="badge bg-success">Valid</span>
                                            <?php elseif($certification->status === 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo e($certification->status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($certification->certifier->name ?? '—'); ?></td>
                                        <td><?php echo e($certification->certification_date->format('d/m/Y')); ?></td>
                                        <td class="text-end">
                                            <a href="<?php echo e(route('certifications.show', $certification)); ?>" class="btn btn-sm btn-outline-primary">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Belum ada data sertifikasi.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rakea\Documents\GitHub\test-aj\resources\views/certifications/index.blade.php ENDPATH**/ ?>