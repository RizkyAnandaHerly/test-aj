<?php $__env->startSection('title', 'Data Sales Order'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Daftar Sales Order</h4>
        <p class="text-muted mb-0 small">Kelola semua pesanan pelanggan Anda di sini.</p>
    </div>
    <a href="<?php echo e(route('sales-orders.create')); ?>" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Buat Pesanan Baru
    </a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase fw-bold text-secondary" style="font-size:.75rem;">No. Pesanan</th>
                        <th class="py-3 text-uppercase fw-bold text-secondary" style="font-size:.75rem;">Nama Pelanggan</th>
                        <th class="py-3 text-uppercase fw-bold text-secondary text-end" style="font-size:.75rem;">Total Harga</th>
                        <th class="py-3 text-uppercase fw-bold text-secondary text-center" style="font-size:.75rem;">Status</th>
                        <th class="pe-4 py-3 text-uppercase fw-bold text-secondary text-center" style="font-size:.75rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $salesOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-4 py-3 fw-semibold text-dark"><?php echo e($order->order_number); ?></td>
                            <td class="py-3"><?php echo e($order->customer_name); ?></td>
                            <td class="py-3 text-end fw-bold text-primary">
                                Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

                            </td>
                            <td class="py-3 text-center">
                                <?php if($order->status === 'completed'): ?>
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3">Completed</span>
                                <?php elseif($order->status === 'cancelled'): ?>
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3">Cancelled</span>
                                <?php elseif($order->status === 'processing'): ?>
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3">Processing</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 py-3 text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('sales-orders.show', $order->id)); ?>" class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('sales-orders.edit', $order->id)); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('sales-orders.destroy', $order->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox d-block mb-2 fs-2 opacity-50"></i>
                                Belum ada data pesanan penjualan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($salesOrders->hasPages()): ?>
        <div class="card-footer bg-white border-top py-3">
            <?php echo e($salesOrders->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rakea\Documents\GitHub\test-aj\resources\views/sales_order/index.blade.php ENDPATH**/ ?>