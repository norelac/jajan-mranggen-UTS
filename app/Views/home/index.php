<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="mb-3">
                    <span class="badge rounded-pill px-3 py-2" style="background:rgba(255,255,255,.2); color:#fff; font-size:.85rem;">
                        🌶️ Jajanan Autentik Khas Mranggen
                    </span>
                </div>
                <h1 class="mb-3">Temukan <span style="color:var(--accent)">Jajanan Lezat</span> dari Mranggen</h1>
                <p class="mb-4">Berbagai pilihan kuliner dan jajanan tradisional khas Mranggen, Demak. Segar, lezat, dan dikirim langsung ke rumah Anda.</p>
                <form action="/produk" method="GET" class="hero-search mb-3">
                    <i class="fas fa-search text-muted"></i>
                    <input type="text" name="q" placeholder="Cari jajanan favoritmu...">
                    <button type="submit">Cari</button>
                </form>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,.9)">
                        <i class="fas fa-check-circle"></i><span style="font-size:.9rem;">Produk Segar</span>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,.9)">
                        <i class="fas fa-check-circle"></i><span style="font-size:.9rem;">Penjual Terpercaya</span>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,.9)">
                        <i class="fas fa-check-circle"></i><span style="font-size:.9rem;">Pengiriman Cepat</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-flex justify-content-end">
                <div style="font-size:12rem; opacity:.15; line-height:1;">🍱</div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section style="background:#fff; padding: 20px 0; box-shadow: 0 4px 15px rgba(0,0,0,.05)">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-4">
                <div class="fw-700" style="font-size:1.6rem; color:var(--primary); font-weight:800;"><?= count($products) ?>+</div>
                <div class="text-muted" style="font-size:.85rem;">Produk</div>
            </div>
            <div class="col-4">
                <div style="font-size:1.6rem; color:var(--primary); font-weight:800;"><?= count($categories) ?>+</div>
                <div class="text-muted" style="font-size:.85rem;">Kategori</div>
            </div>
            <div class="col-4">
                <div style="font-size:1.6rem; color:var(--primary); font-weight:800;">100%</div>
                <div class="text-muted" style="font-size:.85rem;">Asli Mranggen</div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">Kategori <span>Produk</span></h2>
        <div class="section-divider"></div>
        <div class="row g-3">
            <?php
            $icons = ['fa-cake-candles','fa-drumstick-bite','fa-mug-hot','fa-utensils','fa-gift','fa-cookie-bite'];
            $i = 0;
            foreach ($categories as $cat):
            ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="/kategori/<?= esc($cat['slug']) ?>" class="category-card d-block">
                    <div class="category-icon"><i class="fas <?= $icons[$i % count($icons)] ?>"></i></div>
                    <div class="fw-600" style="font-size:.9rem;"><?= esc($cat['name']) ?></div>
                    <div class="mt-1" style="font-size:.75rem; opacity:.7;"><?= $cat['product_count'] ?? 0 ?> produk</div>
                </a>
            </div>
            <?php $i++; endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="section-title mb-0">Produk <span>Unggulan</span></h2>
            <a href="/produk" class="btn btn-primary-custom btn-sm">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        <div class="section-divider"></div>
        <div class="row g-4">
            <?php foreach (array_slice($products, 0, 8) as $product): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card h-100">
                    <?php if ($product['image'] && file_exists(FCPATH . $product['image'])): ?>
                        <img src="/<?= esc($product['image']) ?>" class="card-img-top" alt="<?= esc($product['name']) ?>">
                    <?php else: ?>
                        <div class="product-img-placeholder"><i class="fas fa-bowl-food"></i></div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <span class="badge-category mb-2 d-inline-block"><?= esc($product['category_name']) ?></span>
                        <h6 class="card-title fw-600 flex-grow-1" style="font-size:.9rem; line-height:1.4;">
                            <a href="/produk/<?= esc($product['slug']) ?>" class="text-dark text-decoration-none"><?= esc($product['name']) ?></a>
                        </h6>
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="price">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                            <small class="text-muted" style="font-size:.75rem;">Stok: <?= $product['stock'] ?></small>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <a href="/produk/<?= esc($product['slug']) ?>" class="btn btn-outline-secondary btn-sm flex-grow-1" style="border-radius:20px; font-size:.8rem;">Detail</a>
                            <?php if (session()->get('role') === 'pembeli'): ?>
                            <form action="/pembeli/cart/add" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="btn btn-add-cart" title="Tambah ke keranjang">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="rounded-4 p-4 h-100" style="background:linear-gradient(135deg,#FF6B35,#FFA500); color:#fff;">
                    <i class="fas fa-store fa-2x mb-3 d-block" style="opacity:.8;"></i>
                    <h4 class="fw-700">Ingin Berjualan?</h4>
                    <p style="opacity:.9;">Daftarkan diri Anda sebagai penjual dan mulai berjualan jajanan khas Anda ke seluruh penjuru.</p>
                    <a href="/auth/register" class="btn" style="background:#fff; color:var(--primary); border-radius:25px; font-weight:600; padding:8px 24px;">Daftar Jadi Penjual</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="rounded-4 p-4 h-100" style="background:linear-gradient(135deg,#1A1A2E,#2D2D4E); color:#fff;">
                    <i class="fas fa-truck-fast fa-2x mb-3 d-block" style="opacity:.8;"></i>
                    <h4 class="fw-700">Pengiriman ke Seluruh Kota</h4>
                    <p style="opacity:.9;">Nikmati kemudahan berbelanja jajanan Mranggen yang dikirim langsung ke pintu rumah Anda.</p>
                    <a href="/produk" class="btn btn-primary-custom" style="border-radius:25px; padding:8px 24px;">Belanja Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
