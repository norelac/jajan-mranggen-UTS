<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-orange">
            <div class="stat-icon mb-2"><i class="fas fa-users"></i></div>
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-label">Total Pengguna</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon mb-2"><i class="fas fa-box"></i></div>
            <div class="stat-value"><?= $totalProducts ?></div>
            <div class="stat-label">Total Produk</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-green">
            <div class="stat-icon mb-2"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-value"><?= $totalOrders ?></div>
            <div class="stat-label">Total Pesanan</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-purple">
            <div class="stat-icon mb-2"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-value" style="font-size:1.3rem;">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
</div>

<!-- Second Row Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="table-card text-center">
            <div class="stat-icon mb-2" style="font-size:2rem; color:var(--primary);"><i class="fas fa-store"></i></div>
            <h4 class="fw-700"><?= $totalPenjual ?></h4>
            <p class="text-muted mb-0">Penjual Aktif</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="table-card text-center">
            <div class="stat-icon mb-2" style="font-size:2rem; color:#4A90D9;"><i class="fas fa-user-check"></i></div>
            <h4 class="fw-700"><?= $totalPembeli ?></h4>
            <p class="text-muted mb-0">Pembeli Terdaftar</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="table-card text-center">
            <div class="stat-icon mb-2" style="font-size:2rem; color:#E67E22;"><i class="fas fa-clock"></i></div>
            <h4 class="fw-700"><?= $pendingOrders ?></h4>
            <p class="text-muted mb-0">Pesanan Pending</p>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-700 mb-0">Pesanan Terbaru</h5>
        <a href="/admin/orders" class="btn btn-sm btn-primary-custom">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pembeli</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan</td></tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td><a href="/admin/orders/<?= $order['id'] ?>" class="fw-600 text-decoration-none" style="color:var(--primary);"><?= esc($order['invoice_number']) ?></a></td>
                        <td><?= esc($order['full_name']) ?></td>
                        <td class="fw-600">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                        <td><span class="badge badge-<?= $order['status'] ?> px-3 py-1"><?= ucfirst($order['status']) ?></span></td>
                        <td><span class="badge badge-<?= $order['payment_status'] ?> px-3 py-1"><?= ucfirst($order['payment_status']) ?></span></td>
                        <td class="text-muted small"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
