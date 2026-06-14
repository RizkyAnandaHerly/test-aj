<?php $__env->startSection('title', 'Form Sertifikasi'); ?>
<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        <div class="col-lg-9">

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan pada form:</div>
                    <ul class="mb-0 ps-3 small">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning rounded p-2 me-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-award fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Form Sertifikasi</h5>
                        <p class="text-muted small mb-0">Unggah dokumen sertifikasi dan data ketertelusuran lot kopi.</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="<?php echo e(route('certifications.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="inbound_id" class="form-label fw-semibold small">
                                    Pilih Inbound Sumber Barang <span class="text-danger">*</span>
                                </label>
                                <select name="inbound_id"
                                        id="inbound_id"
                                        class="form-select form-select-lg bg-light border-0 <?php $__errorArgs = ['inbound_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <option value="" selected disabled>-- Pilih Inbound --</option>
                                    <?php $__currentLoopData = $inbounds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inbound): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($inbound->id); ?>"
                                            <?php echo e(old('inbound_id') == $inbound->id ? 'selected' : ''); ?>>
                                            #<?php echo e($inbound->id); ?> – <?php echo e($inbound->product->sku ?? '—'); ?>

                                            | <?php echo e($inbound->product->name ?? '—'); ?>

                                            | Batch: <?php echo e($inbound->batch_no ?? '—'); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['inbound_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="product_id" class="form-label fw-semibold small">
                                    Produk <span class="text-danger">*</span>
                                </label>
                                <select name="product_id"
                                        id="product_id"
                                        class="form-select form-select-lg bg-light border-0 <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <option value="" selected disabled>-- Pilih Produk --</option>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($product->id); ?>"
                                            <?php echo e(old('product_id') == $product->id ? 'selected' : ''); ?>>
                                            <?php echo e($product->sku); ?> — <?php echo e($product->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-4">
                                <label for="certification_date" class="form-label fw-semibold small">
                                    Tanggal Sertifikasi <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="certification_date"
                                       id="certification_date"
                                       class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['certification_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('certification_date', date('Y-m-d'))); ?>"
                                       required>
                                <?php $__errorArgs = ['certification_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="certification_type" class="form-label fw-semibold small">
                                    Tipe Dokumen Sertifikasi <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="certification_type"
                                       id="certification_type"
                                       class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['certification_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('certification_type')); ?>"
                                       placeholder="Cth: Certificate of Origin / Phytosanitary"
                                       required>
                                <?php $__errorArgs = ['certification_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="lot_number" class="form-label fw-semibold small">
                                    Nomor Lot / Traceability <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="lot_number"
                                       id="lot_number"
                                       class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['lot_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('lot_number')); ?>"
                                       placeholder="Cth: LOT-20260519-01"
                                       required>
                                <?php $__errorArgs = ['lot_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="standard_region" class="form-label fw-semibold small">
                                    Standar Dokumen <span class="text-danger">*</span>
                                </label>
                                <select name="standard_region"
                                        id="standard_region"
                                        class="form-select form-select-lg bg-light border-0 <?php $__errorArgs = ['standard_region'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <option value="Eropa" <?php echo e(old('standard_region', 'Eropa') === 'Eropa' ? 'selected' : ''); ?>>Eropa</option>
                                    <option value="Global" <?php echo e(old('standard_region') === 'Global' ? 'selected' : ''); ?>>Global</option>
                                    <option value="Lokal" <?php echo e(old('standard_region') === 'Lokal' ? 'selected' : ''); ?>>Lokal</option>
                                </select>
                                <?php $__errorArgs = ['standard_region'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="document" class="form-label fw-semibold small">
                                    Unggah Dokumen Sertifikasi <span class="text-danger">*</span>
                                </label>
                                <input type="file"
                                       name="document"
                                       id="document"
                                       class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       required>
                                <?php $__errorArgs = ['document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold small">
                                Catatan Ekspor / Validasi
                            </label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="4"
                                      class="form-control bg-light border-0 <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="Tuliskan validasi khusus, persyaratan ekspor, atau catatan traceability..."><?php echo e(old('notes')); ?></textarea>
                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <a href="<?php echo e(route('certifications.index')); ?>"
                               class="btn btn-light border px-4 me-2 fw-semibold">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold shadow-sm">
                                <i class="bi bi-cloud-arrow-up me-1"></i> Simpan Sertifikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rakea\Documents\GitHub\test-aj\resources\views/certifications/create.blade.php ENDPATH**/ ?>