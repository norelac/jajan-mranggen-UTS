<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <!-- Header & Filter -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="section-title">Semua <span>Produk</span></h1>
            <div class="section-divider"></div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 p-3">
        <form action="/produk" method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-600 small">Cari Produk</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Nama produk..."
                           value="<?= esc($keyword ?? '') ?>" style="border-left:none;">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-600 small">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($categoryId == $cat['id']) ? 'selected' : '' ?>>
                            <?= esc($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary-custom w-100">Filter</button>
            </div>
        </form>
    </div>

    <!-- Results info -->
    <p class="text-muted mb-3">
        Menampilkan <strong><?= count($products) ?></strong> produk
        <?= $keyword ? "untuk kata kunci '<strong>" . esc($keyword) . "</strong>'" : '' ?>
    </p>

    <?php if (empty($products)): ?>
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Produk tidak ditemukan</h5>
            <p class="text-muted">Coba kata kunci lain atau pilih kategori yang berbeda</p>
            <a href="/produk" class="btn btn-primary-custom mt-2">Lihat Semua Produk</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
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
                        <div class="text-muted small mb-1"><i class="fas fa-store me-1"></i><?= esc($product['seller_name']) ?></div>
                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <span class="price">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                            <span class="badge <?= $product['stock'] > 0 ? 'bg-success' : 'bg-danger' ?> rounded-pill" style="font-size:.7rem;">
                                <?= $product['stock'] > 0 ? 'Tersedia' : 'Habis' ?>
                            </span>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <a href="/produk/<?= esc($product['slug']) ?>" class="btn btn-outline-secondary btn-sm flex-grow-1" style="border-radius:20px; font-size:.8rem;">Detail</a>
                            <?php if (session()->get('role') === 'pembeli' && $product['stock'] > 0): ?>
                            <form action="/pembeli/cart/add" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="qty" value="1">
                                <button class="btn btn-add-cart" title="Tambah ke keranjang"><i class="fas fa-cart-plus"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
