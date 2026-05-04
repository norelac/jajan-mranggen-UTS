<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" style="color:var(--primary)">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/produk" style="color:var(--primary)">Produk</a></li>
            <li class="breadcrumb-item active"><?= esc($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Image -->
        <div class="col-lg-5">
            <div class="rounded-4 overflow-hidden" style="box-shadow:0 10px 40px rgba(0,0,0,.1);">
                <?php if ($product['image'] && file_exists(FCPATH . $product['image'])): ?>
                    <img src="/<?= esc($product['image']) ?>" class="img-fluid w-100" alt="<?= esc($product['name']) ?>" style="max-height:420px; object-fit:cover;">
                <?php else: ?>
                    <div style="height:350px; background:linear-gradient(135deg,#FFE0D0,#FFB89A); display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-bowl-food" style="font-size:8rem; color:var(--primary); opacity:.5;"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7">
            <span class="badge rounded-pill px-3 py-2 mb-3" style="background:rgba(255,107,53,.1); color:var(--primary); font-size:.85rem;">
                <?= esc($product['category_name']) ?>
            </span>
            <h1 style="font-size:1.8rem; font-weight:700;"><?= esc($product['name']) ?></h1>

            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="font-size:2rem; font-weight:800; color:var(--primary);">
                    Rp <?= number_format($product['price'], 0, ',', '.') ?>
                </div>
                <span class="badge <?= $product['stock'] > 0 ? 'badge-active' : 'badge-inactive' ?> px-3 py-2" style="font-size:.85rem;">
                    <?= $product['stock'] > 0 ? 'Tersedia (' . $product['stock'] . ')' : 'Stok Habis' ?>
                </span>
            </div>

            <!-- Seller Info -->
            <div class="d-flex align-items-center gap-2 mb-4 p-3 rounded-3" style="background:#FFF8F0;">
                <div style="width:40px; height:40px; border-radius:50%; background:var(--primary); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700;">
                    <?= strtoupper(substr($product['seller_name'], 0, 1)) ?>
                </div>
                <div>
                    <div class="fw-600 small"><?= esc($product['seller_name']) ?></div>
                    <div class="text-muted" style="font-size:.75rem;">Penjual Terverifikasi</div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <h6 class="fw-700 mb-2">Deskripsi Produk</h6>
                <p class="text-muted" style="line-height:1.8;"><?= nl2br(esc($product['description'] ?? 'Tidak ada deskripsi.')) ?></p>
            </div>

            <!-- Details -->
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="text-center p-3 rounded-3" style="background:#f8f9fa;">
                        <i class="fas fa-weight-hanging text-muted mb-1 d-block"></i>
                        <div class="fw-600 small"><?= $product['weight'] ?> gram</div>
                        <div class="text-muted" style="font-size:.75rem;">Berat</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 rounded-3" style="background:#f8f9fa;">
                        <i class="fas fa-boxes-stacked text-muted mb-1 d-block"></i>
                        <div class="fw-600 small"><?= $product['stock'] ?> pcs</div>
                        <div class="text-muted" style="font-size:.75rem;">Stok</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 rounded-3" style="background:#f8f9fa;">
                        <i class="fas fa-truck text-muted mb-1 d-block"></i>
                        <div class="fw-600 small">Tersedia</div>
                        <div class="text-muted" style="font-size:.75rem;">Pengiriman</div>
                    </div>
                </div>
            </div>

            <!-- Add to Cart -->
            <?php if (session()->get('role') === 'pembeli' && $product['stock'] > 0): ?>
            <form action="/pembeli/cart/add" method="POST" class="d-flex gap-3 align-items-center">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="d-flex align-items-center border rounded-3 overflow-hidden" style="border-color:#ddd !important;">
                    <button type="button" class="btn border-0 px-3" onclick="this.nextElementSibling.stepDown()">-</button>
                    <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>"
                           class="form-control border-0 text-center" style="width:60px; border-radius:0;">
                    <button type="button" class="btn border-0 px-3" onclick="this.previousElementSibling.stepUp()">+</button>
                </div>
                <button type="submit" class="btn btn-primary-custom flex-grow-1" style="border-radius:25px; padding:12px;">
                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                </button>
            </form>
            <?php elseif (! session()->get('isLoggedIn')): ?>
            <a href="/auth/login" class="btn btn-primary-custom w-100" style="border-radius:25px; padding:12px;">
                <i class="fas fa-right-to-bracket me-2"></i>Login untuk Membeli
            </a>
            <?php elseif ($product['stock'] == 0): ?>
            <button class="btn btn-secondary w-100 rounded-pill" disabled>Stok Habis</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (! empty($related)): ?>
    <div class="mt-5">
        <h3 class="section-title">Produk <span>Serupa</span></h3>
        <div class="section-divider"></div>
        <div class="row g-4">
            <?php foreach ($related as $rel): ?>
            <div class="col-6 col-md-3">
                <div class="card product-card h-100">
                    <?php if ($rel['image'] && file_exists(FCPATH . $rel['image'])): ?>
                        <img src="/<?= esc($rel['image']) ?>" class="card-img-top" alt="<?= esc($rel['name']) ?>">
                    <?php else: ?>
                        <div class="product-img-placeholder"><i class="fas fa-bowl-food"></i></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h6 class="fw-600" style="font-size:.88rem;"><a href="/produk/<?= esc($rel['slug']) ?>" class="text-dark text-decoration-none"><?= esc($rel['name']) ?></a></h6>
                        <div class="price">Rp <?= number_format($rel['price'], 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
