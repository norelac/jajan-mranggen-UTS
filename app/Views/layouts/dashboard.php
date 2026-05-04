<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary:#FF6B35; --primary-dark:#E55A28; --secondary:#FFA500; --dark:#1A1A2E; --sidebar-w:260px; }
        * { font-family: 'Poppins', sans-serif; }
        body { background: #F4F6F9; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-w); background: var(--dark); position: fixed;
            top: 0; left: 0; height: 100vh; overflow-y: auto; z-index: 1000;
            transition: all .3s;
        }
        .sidebar-brand {
            padding: 24px 20px 16px; color: var(--primary); font-size: 1.25rem;
            font-weight: 800; border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-brand span { color: #fff; }
        .sidebar-section { padding: 12px 16px 4px; font-size: .7rem; color: rgba(255,255,255,.4); font-weight: 600; letter-spacing: .08em; text-transform: uppercase; }
        .sidebar-nav { list-style: none; padding: 8px 12px; margin: 0; }
        .sidebar-nav li a {
            display: flex; align-items: center; gap: 12px; padding: 10px 14px;
            color: rgba(255,255,255,.7); text-decoration: none; border-radius: 10px;
            font-size: .9rem; font-weight: 500; transition: all .2s; margin-bottom: 2px;
        }
        .sidebar-nav li a:hover, .sidebar-nav li a.active {
            background: rgba(255,107,53,.2); color: var(--primary);
        }
        .sidebar-nav li a i { width: 20px; text-align: center; font-size: 1rem; }
        .sidebar-footer { padding: 16px; border-top: 1px solid rgba(255,255,255,.08); }
        .sidebar-user { display: flex; align-items: center; gap: 10px; }
        .sidebar-avatar { width: 38px; height: 38px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; }
        .sidebar-user-info small { color: rgba(255,255,255,.5); font-size: .75rem; }
        .sidebar-user-info strong { color: #fff; font-size: .85rem; }

        /* Main content */
        .main-content { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; padding: 14px 28px; display: flex; align-items: center;
            justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,.06); position: sticky; top: 0; z-index: 100;
        }
        .topbar-title { font-weight: 700; font-size: 1.15rem; color: var(--dark); }
        .content-area { padding: 28px; flex: 1; }

        /* Stat cards */
        .stat-card { border: none; border-radius: 16px; padding: 24px; color: #fff; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,.1); }
        .stat-card .stat-icon { font-size: 2rem; opacity: .85; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; }
        .stat-card .stat-label { font-size: .85rem; opacity: .85; font-weight: 500; }
        .stat-orange  { background: linear-gradient(135deg, #FF6B35, #FF8C42); }
        .stat-blue    { background: linear-gradient(135deg, #4A90D9, #74B9FF); }
        .stat-green   { background: linear-gradient(135deg, #27AE60, #55D98D); }
        .stat-purple  { background: linear-gradient(135deg, #8E44AD, #A569BD); }

        /* Tables */
        .table-card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,.05); }
        .table thead th { border: none; background: #F8F9FA; color: #555; font-size: .82rem; font-weight: 600; text-transform: uppercase; padding: 12px 16px; }
        .table td { padding: 12px 16px; vertical-align: middle; font-size: .9rem; }
        .table tbody tr:hover { background: #FFF8F0; }

        /* Buttons */
        .btn-primary-custom { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; border: none; border-radius: 8px; font-weight: 600; }
        .btn-primary-custom:hover { opacity: .9; color: #fff; transform: translateY(-1px); }

        /* Badges */
        .badge-pending    { background: #fff3cd; color: #856404; }
        .badge-processing { background: #cce5ff; color: #004085; }
        .badge-shipped    { background: #d1ecf1; color: #0c5460; }
        .badge-delivered  { background: #d4edda; color: #155724; }
        .badge-cancelled  { background: #f8d7da; color: #721c24; }
        .badge-active     { background: #d4edda; color: #155724; }
        .badge-inactive   { background: #e2e3e5; color: #383d41; }
        .badge-paid       { background: #d4edda; color: #155724; }
        .badge-failed     { background: #f8d7da; color: #721c24; }

        /* Form */
        .form-card { background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 4px 15px rgba(0,0,0,.05); }
        .form-control { border-radius: 10px; border: 2px solid #f0f0f0; padding: 10px 14px; transition: border-color .2s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255,107,53,.1); }
        .form-label { font-weight: 600; font-size: .88rem; color: #555; }

        /* Alert */
        .alert { border-radius: 12px; border: none; font-size: .9rem; }
        .alert-success { background: #eafaf1; color: #1e8449; }
        .alert-danger  { background: #fdf2f2; color: #c0392b; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-bowl-food me-2"></i>Jajan<span>Mranggen</span>
    </div>

    <?php $role = session()->get('role'); $uri = current_url(true)->getPath(); ?>

    <?php if ($role === 'admin'): ?>
        <p class="sidebar-section">Main Menu</p>
        <ul class="sidebar-nav">
            <li><a href="/admin/dashboard" class="<?= str_contains($uri, 'dashboard') ? 'active' : '' ?>"><i class="fas fa-gauge"></i> Dashboard</a></li>
        </ul>
        <p class="sidebar-section">Manajemen</p>
        <ul class="sidebar-nav">
            <li><a href="/admin/users" class="<?= str_contains($uri, '/admin/users') ? 'active' : '' ?>"><i class="fas fa-users"></i> Pengguna</a></li>
            <li><a href="/admin/categories" class="<?= str_contains($uri, '/admin/categories') ? 'active' : '' ?>"><i class="fas fa-tags"></i> Kategori</a></li>
            <li><a href="/admin/products" class="<?= str_contains($uri, '/admin/products') ? 'active' : '' ?>"><i class="fas fa-box"></i> Produk</a></li>
            <li><a href="/admin/orders" class="<?= str_contains($uri, '/admin/orders') ? 'active' : '' ?>"><i class="fas fa-shopping-bag"></i> Pesanan</a></li>
        </ul>
    <?php elseif ($role === 'penjual'): ?>
        <p class="sidebar-section">Main Menu</p>
        <ul class="sidebar-nav">
            <li><a href="/penjual/dashboard" class="<?= str_contains($uri, 'dashboard') ? 'active' : '' ?>"><i class="fas fa-gauge"></i> Dashboard</a></li>
        </ul>
        <p class="sidebar-section">Toko Saya</p>
        <ul class="sidebar-nav">
            <li><a href="/penjual/products" class="<?= str_contains($uri, '/penjual/products') ? 'active' : '' ?>"><i class="fas fa-box"></i> Produk Saya</a></li>
            <li><a href="/penjual/orders" class="<?= str_contains($uri, '/penjual/orders') ? 'active' : '' ?>"><i class="fas fa-shopping-bag"></i> Pesanan Masuk</a></li>
        </ul>
    <?php elseif ($role === 'pembeli'): ?>
        <p class="sidebar-section">Main Menu</p>
        <ul class="sidebar-nav">
            <li><a href="/pembeli/dashboard" class="<?= str_contains($uri, 'dashboard') ? 'active' : '' ?>"><i class="fas fa-gauge"></i> Dashboard</a></li>
        </ul>
        <p class="sidebar-section">Belanja</p>
        <ul class="sidebar-nav">
            <li><a href="/produk"><i class="fas fa-store"></i> Lihat Produk</a></li>
            <li><a href="/pembeli/cart" class="<?= str_contains($uri, '/pembeli/cart') ? 'active' : '' ?>"><i class="fas fa-cart-shopping"></i> Keranjang</a></li>
            <li><a href="/pembeli/orders" class="<?= str_contains($uri, '/pembeli/orders') ? 'active' : '' ?>"><i class="fas fa-box"></i> Pesanan Saya</a></li>
            <li><a href="/pembeli/profile" class="<?= str_contains($uri, '/pembeli/profile') ? 'active' : '' ?>"><i class="fas fa-user"></i> Profil</a></li>
        </ul>
    <?php endif; ?>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar"><?= strtoupper(substr(session()->get('fullName'), 0, 1)) ?></div>
            <div class="sidebar-user-info">
                <strong class="d-block"><?= esc(session()->get('fullName')) ?></strong>
                <small><?= ucfirst(session()->get('role')) ?></small>
            </div>
        </div>
        <a href="/auth/logout" class="btn btn-sm btn-outline-danger w-100 mt-3 rounded-3">
            <i class="fas fa-right-from-bracket me-1"></i>Logout
        </a>
    </div>
</aside>

<!-- Main Content -->
<div class="main-content">
    <div class="topbar">
        <span class="topbar-title"><?= esc($title ?? 'Dashboard') ?></span>
        <div class="d-flex align-items-center gap-3">
            <a href="/" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="fas fa-globe me-1"></i>Lihat Website</a>
            <span class="badge rounded-pill" style="background:rgba(255,107,53,.15); color:var(--primary);"><?= ucfirst(session()->get('role')) ?></span>
        </div>
    </div>

    <div class="content-area">
        <!-- Alerts -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
