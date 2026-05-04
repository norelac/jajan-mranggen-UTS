<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-orange">
            <div class="stat-icon mb-2"><i class="fas fa-box"></i></div>
            <div class="stat-value"><?= $totalProducts ?></div>
            <div class="stat-label">Total Produk</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon mb-2"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-value"><?= $totalOrders ?></div>
            <div class="stat-label">Total Pesanan</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-green">
            <div class="stat-icon mb-2"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-value" style="font-size:1.1rem;">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-purple">
            <div class="stat-icon mb-2"><i class="fas fa-layer-group"></i></div>
            <div class="stat-value"><?= $totalStock ?></div>
            <div class="stat-label">Total Stok</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <a href="/penjual/products/create" class="d-block text-decoration-none">
            <div class="table-card d-flex align-items-center gap-3 p-3" style="border:2px dashed var(--primary); background:#FFF8F0;">
                <div style="width:48px;height:48px;border-radius:12px;background:var(--primary);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-plus text-white"></i>
                </div>
                <div>
                    <div class="fw-700" style="color:var(--primary);">Tambah Produk Baru</div>
                    <div class="text-muted small">Tambahkan produk ke toko Anda</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="/penjual/orders" class="d-block text-decoration-none">
            <div class="table-card d-flex align-items-center gap-3 p-3" style="border:2px dashed #4A90D9; background:#f0f7ff;">
                <div style="width:48px;height:48px;border-radius:12px;background:#4A90D9;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-shopping-bag text-white"></i>
                </div>
                <div>
                    <div class="fw-700" style="color:#4A90D9;">Lihat Pesanan Masuk</div>
                    <div class="text-muted small">Kelola pesanan dari pembeli</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Recent Orders -->
<div class="table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-700 mb-0">Pesanan Terbaru</h5>
        <a href="/penjual/orders" class="btn btn-sm btn-primary-custom">Lihat Semua</a>
    </div>
    <?php if (empty($recentItems)): ?>
        <div class="text-center py-4 text-muted">
            <i class="fas fa-shopping-bag fa-3x mb-3 d-block opacity-25"></i>
            Belum ada pesanan masuk
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr><th>Invoice</th><th>Produk</th><th>Pembeli</th><th>Qty</th><th>Subtotal</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($recentItems as $item): ?>
                    <tr>
                        <td class="fw-600 small" style="color:var(--primary);"><?= esc($item['invoice_number']) ?></td>
                        <td class="fw-600 small"><?= esc($item['product_name']) ?></td>
                        <td class="text-muted small"><?= esc($item['buyer_name']) ?></td>
                        <td><?= $item['quantity'] ?>x</td>
                        <td class="fw-600">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                        <td><span class="badge badge-<?= $item['order_status'] ?>"><?= ucfirst($item['order_status']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
