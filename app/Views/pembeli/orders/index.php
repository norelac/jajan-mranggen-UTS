<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Pesanan Saya</h4>
        <small class="text-muted">Riwayat pesanan yang pernah Anda buat</small>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3 d-block opacity-25"></i>
                            <p class="text-muted mb-0">Belum ada pesanan</p>
                            <a href="/produk" class="btn btn-sm btn-primary-custom mt-3">Mulai Belanja</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><a href="/pembeli/orders/<?= $o['id'] ?>" class="fw-600 text-decoration-none" style="color:var(--primary);"><?= esc($o['invoice_number']) ?></a></td>
                        <td class="text-muted small"><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                        <td class="fw-600">Rp <?= number_format($o['total_price'], 0, ',', '.') ?></td>
                        <td>
                            <div class="small fw-600"><?= str_replace('_', ' ', strtoupper($o['payment_method'])) ?></div>
                            <span class="badge badge-<?= $o['payment_status'] ?>"><?= ucfirst($o['payment_status']) ?></span>
                        </td>
                        <td><span class="badge badge-<?= $o['status'] ?> px-3 py-1"><?= ucfirst($o['status']) ?></span></td>
                        <td>
                            <a href="/pembeli/orders/<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-eye"></i> Detail</a>
                            <?php if (in_array($o['status'], ['pending', 'processing'])): ?>
                                <form action="/pembeli/orders/cancel/<?= $o['id'] ?>" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">Batal</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
