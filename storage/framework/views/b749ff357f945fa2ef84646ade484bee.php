<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'WarehouseTrack'); ?> — WarehouseTrack</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --sidebar-bg:      #1e293b;
            --sidebar-width:   260px;
            --sidebar-text:    #94a3b8;
            --sidebar-active:  #3b82f6;
            --sidebar-hover:   #334155;
            --content-bg:      #f1f5f9;
            --header-height:   64px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--content-bg);
            margin: 0;
            overflow-x: hidden;
        }

        /* ── Sidebar ──────────────────────────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid #334155;
            text-decoration: none;
            flex-shrink: 0;
        }
        .sidebar-brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .sidebar-brand-text {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }
        .sidebar-brand-sub {
            font-size: 0.65rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* Nav sections */
        .sidebar-nav { flex: 1; padding: 12px 0; }

        .sidebar-section-label {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #475569;
            padding: 14px 20px 6px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0;
            transition: background 0.15s, color 0.15s;
            position: relative;
            cursor: pointer;
        }
        .sidebar-item:hover {
            background: var(--sidebar-hover);
            color: #e2e8f0;
            text-decoration: none;
        }
        .sidebar-item.active {
            background: rgba(59, 130, 246, 0.15);
            color: #fff;
        }
        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--sidebar-active);
            border-radius: 0 2px 2px 0;
        }
        .sidebar-item .nav-icon {
            width: 18px;
            font-size: 1rem;
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar-item.disabled-item {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }
        .sidebar-item .badge-soon {
            margin-left: auto;
            font-size: 0.6rem;
            padding: 2px 6px;
            background: #334155;
            color: #64748b;
            border-radius: 4px;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* Sidebar footer / user info */
        .sidebar-footer {
            border-top: 1px solid #334155;
            padding: 14px 16px;
            flex-shrink: 0;
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.875rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #e2e8f0;
            line-height: 1.2;
        }
        .sidebar-user-role {
            font-size: 0.65rem;
            color: #64748b;
            text-transform: capitalize;
        }

        /* ── Main content ─────────────────────────────────────────────── */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* Top header bar */
        #top-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 1030;
            gap: 12px;
        }

        #hamburger-btn {
            display: none;
            background: none;
            border: none;
            padding: 6px;
            cursor: pointer;
            color: #64748b;
            font-size: 1.25rem;
            border-radius: 6px;
            transition: background 0.15s;
        }
        #hamburger-btn:hover { background: #f1f5f9; color: #1e293b; }

        .header-page-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            flex: 1;
        }

        .header-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
        }
        .header-role-badge {
            font-size: 0.65rem;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .role-admin    { background: #fef3c7; color: #92400e; }
        .role-manager  { background: #dbeafe; color: #1e40af; }
        .role-staff    { background: #dcfce7; color: #166534; }
        .role-requester{ background: #f3e8ff; color: #6b21a8; }

        /* Page content */
        #page-content {
            flex: 1;
            padding: 24px;
        }

        /* Overlay for mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1039;
        }
        #sidebar-overlay.active { display: block; }

        /* ── Responsive ───────────────────────────────────────────────── */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.open {
                transform: translateX(0);
            }
            #main-wrapper {
                margin-left: 0;
            }
            #hamburger-btn {
                display: flex;
                align-items: center;
            }
        }

        /* ── Utility ─────────────────────────────────────────────────── */
        .page-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
        }
    </style>

    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>




<aside id="sidebar">

    
    <a href="<?php echo e(auth()->user()?->hasRole('admin') ? route('admin.dashboard')
               : (auth()->user()?->hasRole('manager') ? route('manager.dashboard')
               : (auth()->user()?->hasRole('staff') ? route('staff.dashboard')
               : route('dashboard')))); ?>"
       class="sidebar-brand">
        <div class="sidebar-brand-icon">🏭</div>
        <div>
            <div class="sidebar-brand-text">WarehouseTrack</div>
            <div class="sidebar-brand-sub">WMS v2.0</div>
        </div>
    </a>

    
    <nav class="sidebar-nav">

        
        <?php if(auth()->guard()->check()): ?>
        <?php if(Auth::user()->hasRole('admin')): ?>

            <div class="sidebar-section-label">Master Data</div>

            <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
            </a>

            <a href="#" class="sidebar-item disabled-item">
                <i class="bi bi-building nav-icon"></i> Master Gudang
                <span class="badge-soon">Soon</span>
            </a>

            <a href="#" class="sidebar-item disabled-item">
                <i class="bi bi-truck nav-icon"></i> Master Vendor
                <span class="badge-soon">Soon</span>
            </a>

            <a href="<?php echo e(route('products.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">
                <i class="bi bi-boxes nav-icon"></i> Katalog Barang
            </a>

            <div class="sidebar-section-label">Operasional</div>

            <a href="<?php echo e(route('inbounds.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('inbounds.*') ? 'active' : ''); ?>">
                <i class="bi bi-box-arrow-in-down nav-icon"></i> Data Inbound
            </a>

            <a href="<?php echo e(route('locations.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('locations.*') ? 'active' : ''); ?>">
                <i class="bi bi-geo-alt nav-icon"></i> Penempatan Lokasi
            </a>

            <a href="<?php echo e(route('qc-inspections.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('qc-inspections.*') ? 'active' : ''); ?>">
                <i class="bi bi-shield-check nav-icon"></i> Form QC
            </a>

            <div class="sidebar-section-label">Monitoring</div>

            <a href="<?php echo e(route('search.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('search.*') ? 'active' : ''); ?>">
                <i class="bi bi-search nav-icon"></i> Pencarian Posisi
            </a>

            <a href="<?php echo e(route('activity-logs.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('activity-logs.*') ? 'active' : ''); ?>">
                <i class="bi bi-journal-text nav-icon"></i> Activity Log
            </a>

            <a href="<?php echo e(route('reports.movements.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('reports.movements.*') ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-bar-graph nav-icon"></i> Laporan & Export
            </a>

        
        <?php elseif(Auth::user()->hasRole('manager')): ?>

            <div class="sidebar-section-label">Dashboard</div>

            <a href="<?php echo e(route('manager.dashboard')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('manager.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
            </a>

            <a href="<?php echo e(route('products.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">
                <i class="bi bi-boxes nav-icon"></i> Katalog Barang
            </a>

            <div class="sidebar-section-label">Monitoring</div>

            <a href="<?php echo e(route('search.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('search.*') ? 'active' : ''); ?>">
                <i class="bi bi-search nav-icon"></i> Pencarian Posisi Barang
            </a>

            <a href="<?php echo e(route('activity-logs.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('activity-logs.*') ? 'active' : ''); ?>">
                <i class="bi bi-journal-text nav-icon"></i> Activity Log
            </a>

            <a href="<?php echo e(route('reports.movements.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('reports.movements.*') ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-arrow-down nav-icon"></i> Laporan & Export
            </a>

        
        <?php elseif(Auth::user()->hasRole('staff')): ?>

            <div class="sidebar-section-label">Dashboard</div>

            <a href="<?php echo e(route('staff.dashboard')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('staff.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
            </a>

            <a href="<?php echo e(route('products.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">
                <i class="bi bi-boxes nav-icon"></i> Katalog Barang
            </a>

            <div class="sidebar-section-label">Operasional Harian</div>

            <a href="<?php echo e(route('inbounds.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('inbounds.*') ? 'active' : ''); ?>">
                <i class="bi bi-box-arrow-in-down nav-icon"></i> Data Inbound
            </a>

            <a href="<?php echo e(route('locations.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('locations.*') ? 'active' : ''); ?>">
                <i class="bi bi-geo-alt nav-icon"></i> Penempatan Lokasi
            </a>

            <a href="<?php echo e(route('qc-inspections.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('qc-inspections.*') ? 'active' : ''); ?>">
                <i class="bi bi-shield-check nav-icon"></i> Form QC
            </a>

            <a href="<?php echo e(route('reject-items.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('reject-items.*') ? 'active' : ''); ?>">
                <i class="bi bi-slash-circle nav-icon"></i> Reject & Karantina
            </a>

            <a href="<?php echo e(route('packing-details.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('packing-details.*') ? 'active' : ''); ?>">
                <i class="bi bi-box-seam nav-icon"></i> Packing & Pelabelan
            </a>
            
            <a href="<?php echo e(route('certifications.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('certifications.*') ? 'active' : ''); ?>">
                <i class="bi bi-award nav-icon"></i> Sertifikasi
            </a>

            <a href="<?php echo e(route('sales-orders.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('sales-orders.*') ? 'active' : ''); ?>">
                <i class="bi bi-cart nav-icon"></i> Sales Order
            </a>

            <a href="<?php echo e(route('shipping-documents.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('shipping-documents.*') ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-text nav-icon"></i> Dokumen Pengiriman
            </a>

            <a href="<?php echo e(route('stock-opnames.index')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('stock-opnames.*') ? 'active' : ''); ?>">
                <i class="bi bi-clipboard-data nav-icon"></i> Stock Opname
            </a>

        
        <?php else: ?>

            <div class="sidebar-section-label">Tracking</div>

            <a href="<?php echo e(route('dashboard')); ?>"
               class="sidebar-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2 nav-icon"></i> Dashboard
            </a>

            <a href="#" class="sidebar-item disabled-item">
                <i class="bi bi-cart nav-icon"></i> Status Order Saya
                <span class="badge-soon">Soon</span>
            </a>

        <?php endif; ?>
        <?php endif; ?>
    </nav>

    
    <?php if(auth()->guard()->check()): ?>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

            </div>
            <div style="min-width:0; flex:1;">
                <div class="sidebar-user-name text-truncate"><?php echo e(Auth::user()->name); ?></div>
                <div class="sidebar-user-role">
                    <?php echo e(Auth::user()->role?->name ?? 'user'); ?>

                </div>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="flex-shrink-0">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="btn btn-sm"
                        style="background:none; border:1px solid #334155; color:#64748b; padding:4px 8px; border-radius:6px;"
                        title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

</aside>


<div id="sidebar-overlay"></div>




<div id="main-wrapper">

    
    <header id="top-header">
        <button id="hamburger-btn" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>

        <div class="header-page-title">
            <?php echo $__env->yieldContent('title', 'Dashboard'); ?>
        </div>

        <?php if(auth()->guard()->check()): ?>
        <div class="header-user-info">
            <?php $role = Auth::user()->role?->name ?? 'user'; ?>
            <div class="header-user-name d-none d-sm-block"><?php echo e(Auth::user()->name); ?></div>
            <span class="header-role-badge role-<?php echo e($role); ?>"><?php echo e(ucfirst($role)); ?></span>

            <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-none d-md-block">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="btn btn-sm"
                        style="background:#f8fafc; border:1px solid #e2e8f0; color:#64748b; font-size:0.8rem; padding:5px 12px; border-radius:8px; font-weight:600;">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>
        <?php endif; ?>
    </header>

    
    <main id="page-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
(function () {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const hamburger = document.getElementById('hamburger-btn');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (hamburger) hamburger.addEventListener('click', openSidebar);
    if (overlay)   overlay.addEventListener('click', closeSidebar);

    // Close on resize back to desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) closeSidebar();
    });
})();
</script>

<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\rakea\Documents\GitHub\test-aj\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>