<?php $__env->startSection('title', 'Input Inbound'); ?>

<?php $__env->startSection('styles'); ?>
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    .ts-wrapper.form-select-lg .ts-control {
        min-height: 48px !important;
        padding: 0.5rem 1rem !important;
        font-size: 1rem !important;
    }
    .ts-control {
        border: 0 !important;
        background-color: #f8fafc !important; /* match bg-light */
        border-radius: 0.5rem !important;
        box-shadow: none !important;
    }
    .ts-dropdown {
        border-radius: 0.5rem !important;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.05) !important;
        border: 1px solid #e2e8f0 !important;
        padding: 0.25rem !important;
    }
    .ts-dropdown .active {
        background-color: #3b82f6 !important;
        color: white !important;
        border-radius: 0.25rem !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        <div class="col-lg-9">

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
                    <span class="fw-semibold"><?php echo e(session('success')); ?></span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded p-2 me-3">
                        <i class="bi bi-box-arrow-in-down fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Form Inbound Barang</h5>
                        <p class="text-muted small mb-0">Catat penerimaan barang baru dari vendor ke dalam sistem</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="<?php echo e(route('inbounds.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2">Detail Penerimaan</h6>

                        
                        <div class="mb-4">
                            <label for="product_id" class="form-label fw-semibold small">
                                Pilih Produk <span class="text-danger">*</span>
                            </label>
                            <select name="product_id" id="product_id"
                                    class="form-select form-select-lg bg-light border-0 <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                <option value="" selected disabled>-- Cari atau Pilih Produk --</option>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->id); ?>" <?php echo e(old('product_id') == $p->id ? 'selected' : ''); ?>>
                                        <?php echo e($p->sku); ?> — <?php echo e($p->name); ?>

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

                        
                        <div class="mb-4">
                            <label for="vendor_id" class="form-label fw-semibold small">
                                Vendor / Supplier <span class="text-danger">*</span>
                            </label>
                            <select name="vendor_id" id="vendor_id"
                                    class="form-select form-select-lg bg-light border-0 <?php $__errorArgs = ['vendor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="" selected disabled>-- Pilih Vendor --</option>
                                <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($v->id); ?>" <?php echo e(old('vendor_id') == $v->id ? 'selected' : ''); ?>>
                                        <?php echo e($v->code); ?> — <?php echo e($v->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['vendor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="qty" class="form-label fw-semibold small">
                                    Kuantitas Diterima <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="qty" id="qty" min="1"
                                       class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       placeholder="Contoh: 150"
                                       value="<?php echo e(old('qty')); ?>" required>
                                <?php $__errorArgs = ['qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="batch_no" class="form-label fw-semibold small">Nomor Batch</label>
                                <input type="text" name="batch_no" id="batch_no"
                                       class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['batch_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       placeholder="Contoh: BATCH-2026-05A"
                                       value="<?php echo e(old('batch_no')); ?>">
                                <?php $__errorArgs = ['batch_no'];
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
                            <label for="received_date" class="form-label fw-semibold small">
                                Tanggal Penerimaan <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="received_date" id="received_date"
                                   class="form-control form-control-lg bg-light border-0 <?php $__errorArgs = ['received_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('received_date', date('Y-m-d'))); ?>" required>
                            <?php $__errorArgs = ['received_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2 mt-2">Lokasi Penempatan (Opsional)</h6>

                        <div class="row mb-3 g-3">
                            
                            <div class="col-md-6">
                                <label for="warehouse_sel" class="form-label fw-semibold small">Gudang</label>
                                <select id="warehouse_sel"
                                        class="form-select bg-light border-0">
                                    <option value="">-- Pilih Gudang --</option>
                                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($wh->id); ?>"><?php echo e($wh->code); ?> — <?php echo e($wh->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="col-md-6">
                                <label for="zone_sel" class="form-label fw-semibold small">Zona</label>
                                <select id="zone_sel" class="form-select bg-light border-0" disabled>
                                    <option value="">-- Pilih Gudang dulu --</option>
                                </select>
                            </div>

                            
                            <div class="col-md-6">
                                <label for="rack_sel" class="form-label fw-semibold small">Rak</label>
                                <select id="rack_sel" class="form-select bg-light border-0" disabled>
                                    <option value="">-- Pilih Zona dulu --</option>
                                </select>
                            </div>

                            
                            <div class="col-md-6" id="pallet_wrap" style="display:none;">
                                <label for="pallet_sel" class="form-label fw-semibold small">Palet</label>
                                <select id="pallet_sel" class="form-select bg-light border-0">
                                    <option value="">-- Tanpa Palet --</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="location_id" id="location_id" value="<?php echo e(old('location_id')); ?>">

                        
                        <div id="capacity-info" class="mb-4 rounded-3 px-3 py-2 small fw-semibold" style="display:none; background:#f0fdf4; border:1px solid #bbf7d0;">
                            <span id="capacity-text"></span>
                        </div>

                        
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold small">Catatan Penerimaan</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="form-control bg-light border-0 <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="Keterangan kondisi fisik barang saat tiba..."><?php echo e(old('notes')); ?></textarea>
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
                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-light border px-4 me-2 fw-semibold">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Data Inbound
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#product_id', {
            create: false,
            placeholder: '-- Cari atau Pilih Produk --',
            controlInput: '<input>'
        });
        new TomSelect('#vendor_id', {
            create: false,
            placeholder: '-- Pilih Vendor --',
            controlInput: '<input>'
        });
    });
    </script>

    
    <script>
    (function () {
        const warehouseSel = document.getElementById('warehouse_sel');
        const zoneSel      = document.getElementById('zone_sel');
        const rackSel      = document.getElementById('rack_sel');
        const palletSel    = document.getElementById('pallet_sel');
        const palletWrap   = document.getElementById('pallet_wrap');
        const locationId   = document.getElementById('location_id');
        const capInfo      = document.getElementById('capacity-info');
        const capText      = document.getElementById('capacity-text');

        function resetSelect(el, placeholder, disabled = true) {
            el.innerHTML = `<option value="">${placeholder}</option>`;
            el.disabled  = disabled;
        }

        function showCapacity(remaining, capacity) {
            const used = capacity - remaining;
            const pct  = capacity > 0 ? Math.round((used / capacity) * 100) : 0;
            capText.textContent = `Kapasitas tersedia: ${remaining} dari ${capacity} (${pct}% terpakai)`;
            capInfo.style.background   = remaining < 20 ? '#fef2f2' : '#f0fdf4';
            capInfo.style.borderColor  = remaining < 20 ? '#fecaca' : '#bbf7d0';
            capText.style.color        = remaining < 20 ? '#dc2626' : '#16a34a';
            capInfo.style.display      = 'block';
        }

        // Gudang → Zona
        warehouseSel.addEventListener('change', async function () {
            resetSelect(zoneSel, '-- Pilih Zona --');
            resetSelect(rackSel, '-- Pilih Zona dulu --');
            resetSelect(palletSel, '-- Tanpa Palet --');
            palletWrap.style.display = 'none';
            capInfo.style.display    = 'none';
            locationId.value         = '';

            if (!this.value) return;

            const res   = await fetch(`/api/locations/zones?warehouse_id=${this.value}`);
            const zones = await res.json();

            zones.forEach(z => {
                const opt = document.createElement('option');
                opt.value = z; opt.textContent = `Zona ${z}`;
                zoneSel.appendChild(opt);
            });
            zoneSel.disabled = zones.length === 0;
        });

        // Zona → Rak
        zoneSel.addEventListener('change', async function () {
            resetSelect(rackSel, '-- Pilih Rak --');
            resetSelect(palletSel, '-- Tanpa Palet --');
            palletWrap.style.display = 'none';
            capInfo.style.display    = 'none';

            if (!this.value) return;

            const whId = warehouseSel.value;
            const res  = await fetch(`/api/locations/racks?warehouse_id=${whId}&zone=${this.value}`);
            const racks = await res.json();

            racks.forEach(r => {
                const opt = document.createElement('option');
                opt.value       = r.id;
                opt.textContent = `${r.rack_code} (Sisa: ${r.remaining}/${r.capacity})`;
                opt.dataset.remaining = r.remaining;
                opt.dataset.capacity  = r.capacity;
                rackSel.appendChild(opt);
            });
            rackSel.disabled = racks.length === 0;
        });

        // Rak → Palet + kapasitas hint
        rackSel.addEventListener('change', async function () {
            resetSelect(palletSel, '-- Tanpa Palet --');
            palletWrap.style.display = 'none';
            capInfo.style.display    = 'none';
            locationId.value         = this.value;

            if (!this.value) return;

            // Show capacity for this rack
            const opt = this.options[this.selectedIndex];
            if (opt.dataset.remaining !== undefined) {
                showCapacity(parseInt(opt.dataset.remaining), parseInt(opt.dataset.capacity));
            }

            const whId = warehouseSel.value;
            const zone = zoneSel.value;
            const rack = opt.textContent.split(' ')[0]; // Extract rack_code
            locationId.value = this.value;
            const res  = await fetch(`/api/locations/pallets?warehouse_id=${whId}&zone=${zone}&rack=${rack}`);
            const pallets = await res.json();

            if (pallets.length > 0) {
                pallets.forEach(p => {
                    const o = document.createElement('option');
                    o.value = p.id;
                    o.textContent = `${p.pallet_code} (Sisa: ${p.remaining}/${p.capacity})`;
                    palletSel.appendChild(o);
                });
                palletWrap.style.display = 'block';
            }
        });

        palletSel.addEventListener('change', function () {
            locationId.value = this.value || rackSel.value || '';
        });
    }());
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rakea\Documents\GitHub\test-aj\resources\views/inbounds/create.blade.php ENDPATH**/ ?>