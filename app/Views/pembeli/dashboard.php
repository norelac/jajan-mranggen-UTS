<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="stat-card stat-orange">
            <div class="stat-icon mb-2"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-value"><?= $totalOrders ?></div>
            <div class="stat-label">Total Pesanan</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card stat-green">
            <div class="stat-icon mb-2"><i class="fas fa-wallet"></i></div>
            <div class="stat-value" style="font-size:1.3rem;">Rp <?= number_format($totalSpent, 0, ',', '.') ?></div>
            <div class="stat-label">Total Belanja (Berhasil)</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="table-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-700 mb-0">Pesanan Terakhir</h5>
                <a href="/pembeli/orders" class="btn btn-sm btn-primary-custom">Lihat Semua</a>
            </div>
            <?php if (empty($recentOrders)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-box-open fa-3x mb-3 d-block opacity-25"></i>
                    <p class="mb-2">Anda belum pernah berbelanja.</p>
                    <a href="/produk" class="btn btn-primary-custom btn-sm">Mulai Belanja</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <tbody>
                            <?php foreach ($recentOrders as $o): ?>
                            <tr>
                                <td>
                                    <div class="fw-600 mb-1" style="color:var(--primary);"><?= esc($o['invoice_number']) ?></div>
                                    <div class="text-muted small"><?= date('d M Y', strtotime($o['created_at'])) ?></div>
                                </td>
                                <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                                <td class="fw-600 text-end">Rp <?= number_format($o['total_price'], 0, ',', '.') ?></td>
                                <td class="text-end"><a href="/pembeli/orders/<?= $o['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Detail</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="col-lg-4">
        <div class="table-card h-100">
            <h5 class="fw-700 mb-4">Akses Cepat</h5>
            <div class="d-grid gap-3">
                <a href="/produk" class="btn btn-outline-primary text-start p-3 rounded-3 d-flex align-items-center gap-3" style="border-color:#e0e0e0; color:var(--dark);">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;"><i class="fas fa-store"></i></div>
                    <div class="fw-600">Jelajahi Produk</div>
                </a>
                <a href="/pembeli/cart" class="btn btn-outline-primary text-start p-3 rounded-3 d-flex align-items-center gap-3" style="border-color:#e0e0e0; color:var(--dark);">
                    <div style="width:40px;height:40px;border-radius:10px;background:#FFA500;color:#fff;display:flex;align-items:center;justify-content:center;"><i class="fas fa-shopping-cart"></i></div>
                    <div class="fw-600">Lihat Keranjang</div>
                </a>
                <a href="/pembeli/profile" class="btn btn-outline-primary text-start p-3 rounded-3 d-flex align-items-center gap-3" style="border-color:#e0e0e0; color:var(--dark);">
                    <div style="width:40px;height:40px;border-radius:10px;background:#4A90D9;color:#fff;display:flex;align-items:center;justify-content:center;"><i class="fas fa-user-edit"></i></div>
                    <div class="fw-600">Edit Profil & Alamat</div>
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
