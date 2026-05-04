<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Jajan Mranggen - Jajanan Khas Mranggen, Demak') ?></title>
    <meta name="description" content="Jajan Mranggen - Platform kuliner dan jajanan khas Mranggen, Demak. Temukan produk autentik dari penjual lokal terpercaya.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #E55A28;
            --secondary: #FFA500;
            --accent: #FFD700;
            --dark: #1A1A2E;
            --light: #FFF8F0;
            --gray: #6c757d;
            --success-color: #28a745;
        }
        * { font-family: 'Poppins', sans-serif; }
        body { background: var(--light); color: var(--dark); }

        /* Navbar */
        .navbar-brand .brand-logo { color: var(--primary); font-weight: 800; font-size: 1.5rem; }
        .navbar-brand span { color: var(--dark); }
        .navbar { background: #fff !important; box-shadow: 0 2px 20px rgba(0,0,0,.08); }
        .nav-link { font-weight: 500; color: var(--dark) !important; transition: color .2s; }
        .nav-link:hover { color: var(--primary) !important; }
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff; border: none; border-radius: 25px;
            padding: 8px 22px; font-weight: 600; transition: all .3s;
        }
        .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(255,107,53,.35); color: #fff; }
        .cart-badge { background: var(--primary); color: #fff; border-radius: 50%; padding: 2px 6px; font-size: .7rem; }

        /* Hero */
        .hero-section {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 50%, #FFA500 100%);
            color: #fff; padding: 100px 0 80px; position: relative; overflow: hidden;
        }
        .hero-section::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-section h1 { font-size: 3rem; font-weight: 800; line-height: 1.2; }
        .hero-section p { font-size: 1.15rem; opacity: .9; }
        .hero-search { background: #fff; border-radius: 50px; padding: 8px 8px 8px 20px; display: flex; gap: 8px; }
        .hero-search input { border: none; outline: none; flex: 1; font-size: 1rem; color: var(--dark); background: transparent; }
        .hero-search button { background: var(--primary); color: #fff; border: none; border-radius: 40px; padding: 10px 25px; font-weight: 600; }

        /* Cards */
        .product-card {
            border: none; border-radius: 16px; overflow: hidden; transition: all .3s;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 15px 35px rgba(255,107,53,.2); }
        .product-card .card-img-top { height: 200px; object-fit: cover; }
        .product-card .price { color: var(--primary); font-weight: 700; font-size: 1.1rem; }
        .product-card .badge-category { background: var(--light); color: var(--primary); border-radius: 20px; padding: 4px 12px; font-size: .75rem; font-weight: 600; }
        .product-card .card-body { padding: 16px; }
        .product-card .btn-add-cart { background: var(--primary); color: #fff; border: none; border-radius: 20px; padding: 6px 16px; font-size: .85rem; font-weight: 500; transition: all .2s; }
        .product-card .btn-add-cart:hover { background: var(--primary-dark); transform: scale(1.05); }
        .product-img-placeholder { height: 200px; background: linear-gradient(135deg, #FFE0D0, #FFB89A); display: flex; align-items: center; justify-content: center; }
        .product-img-placeholder i { font-size: 4rem; color: var(--primary); opacity: .6; }

        /* Category cards */
        .category-card {
            border: none; border-radius: 16px; padding: 24px; text-align: center;
            background: #fff; transition: all .3s; box-shadow: 0 4px 15px rgba(0,0,0,.06);
            cursor: pointer; text-decoration: none; color: var(--dark);
        }
        .category-card:hover { transform: translateY(-5px); background: var(--primary); color: #fff; box-shadow: 0 12px 30px rgba(255,107,53,.3); }
        .category-card .category-icon { font-size: 2.5rem; margin-bottom: 10px; }
        .category-card:hover .category-icon { color: #fff; }

        /* Section titles */
        .section-title { font-size: 2rem; font-weight: 700; color: var(--dark); }
        .section-title span { color: var(--primary); }
        .section-divider { width: 60px; height: 4px; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 2px; margin: 10px 0 30px; }

        /* Flash messages */
        .alert { border-radius: 12px; border: none; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger  { background: #f8d7da; color: #721c24; }

        /* Footer */
        footer { background: var(--dark); color: #ccc; }
        footer h5 { color: #fff; font-weight: 600; }
        footer a { color: #aaa; text-decoration: none; transition: color .2s; }
        footer a:hover { color: var(--primary); }
        .footer-brand { color: var(--primary); font-weight: 800; font-size: 1.4rem; }
        .social-icon { width: 38px; height: 38px; border-radius: 50%; background: rgba(255,255,255,.1); display: inline-flex; align-items: center; justify-content: center; color: #fff; transition: all .2s; }
        .social-icon:hover { background: var(--primary); color: #fff; transform: translateY(-2px); }

        /* Misc */
        .badge-status-pending    { background: #fff3cd; color: #856404; }
        .badge-status-processing { background: #cce5ff; color: #004085; }
        .badge-status-shipped    { background: #d1ecf1; color: #0c5460; }
        .badge-status-delivered  { background: #d4edda; color: #155724; }
        .badge-status-cancelled  { background: #f8d7da; color: #721c24; }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <span class="brand-logo"><i class="fa-solid fa-bowl-food fa-lg me-1"></i>Jajan</span>
            <span style="color:var(--primary); font-weight:800;">Mranggen</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/"><i class="fa-solid fa-house me-1"></i>Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="/produk"><i class="fa-solid fa-store me-1"></i>Produk</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if (session()->get('isLoggedIn')): ?>
                    <?php if (session()->get('role') === 'pembeli'): ?>
                    <a href="/pembeli/cart" class="btn btn-outline-secondary btn-sm position-relative me-1">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php $cart = session()->get('cart') ?? []; ?>
                        <?php if (count($cart) > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle cart-badge"><?= count($cart) ?></span>
                        <?php endif; ?>
                    </a>
                    <?php endif; ?>
                    <div class="dropdown">
                        <button class="btn btn-primary-custom dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user-circle me-1"></i><?= esc(session()->get('fullName')) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <?php $role = session()->get('role'); ?>
                            <?php if ($role === 'admin'): ?>
                                <li><a class="dropdown-item" href="/admin/dashboard"><i class="fa-solid fa-gauge me-2"></i>Dashboard Admin</a></li>
                            <?php elseif ($role === 'penjual'): ?>
                                <li><a class="dropdown-item" href="/penjual/dashboard"><i class="fa-solid fa-gauge me-2"></i>Dashboard Penjual</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="/pembeli/dashboard"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item" href="/pembeli/orders"><i class="fa-solid fa-box me-2"></i>Pesanan Saya</a></li>
                                <li><a class="dropdown-item" href="/pembeli/profile"><i class="fa-solid fa-user me-2"></i>Profil</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/auth/logout"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="/auth/login" class="btn btn-outline-secondary btn-sm">Masuk</a>
                    <a href="/auth/register" class="btn btn-primary-custom btn-sm">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<div class="container mt-3">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<!-- Main Content -->
<?= $this->renderSection('content') ?>

<!-- Footer -->
<footer class="pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand"><i class="fa-solid fa-bowl-food fa-lg me-2"></i>Jajan Mranggen</div>
                <p class="mt-2" style="font-size:.9rem;">Platform kuliner dan jajanan khas Mranggen, Demak. Temukan produk autentik dari penjual lokal terpercaya dengan sistem rating dan peta interaktif.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-2">
                <h5>Menu</h5>
                <ul class="list-unstyled mt-2">
                    <li class="mb-1"><a href="/">Beranda</a></li>
                    <li class="mb-1"><a href="/produk">Semua Produk</a></li>
                    <li class="mb-1"><a href="/auth/register">Daftar Penjual</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h5>Kontak</h5>
                <ul class="list-unstyled mt-2" style="font-size:.9rem;">
                    <li class="mb-1"><i class="fas fa-map-marker-alt me-2" style="color:var(--primary)"></i>Jl. Raya Mranggen, Demak, Jateng</li>
                    <li class="mb-1"><i class="fas fa-phone me-2" style="color:var(--primary)"></i>0812-3456-7890</li>
                    <li class="mb-1"><i class="fas fa-envelope me-2" style="color:var(--primary)"></i>hello@jajanmranggen.com</li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h5>Jam Operasional</h5>
                <p style="font-size:.9rem;">Senin - Minggu: 07.00 - 21.00 WIB<br>Pengiriman: 08.00 - 17.00 WIB</p>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,.1);" class="mt-4">
        <p class="text-center mb-0" style="font-size:.85rem;">© <?= date('Y') ?> Jajan Mranggen. All rights reserved. Made with <i class="fas fa-heart" style="color:var(--primary)"></i> in Mranggen</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
