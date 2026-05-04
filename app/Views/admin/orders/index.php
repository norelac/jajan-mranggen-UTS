<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Manajemen Pesanan</h4>
        <small class="text-muted">Kelola semua pesanan dari pembeli</small>
    </div>
    <div class="d-flex gap-2">
        <?php foreach (['', 'pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?>
            <a href="/admin/orders<?= $s ? '?status=' . $s : '' ?>"
               class="btn btn-sm <?= $status === $s || ($s === '' && ! $status) ? 'btn-primary-custom' : 'btn-outline-secondary' ?>"
               style="border-radius:20px; font-size:.8rem;">
                <?= $s ? ucfirst($s) : 'Semua' ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>#</th><th>Invoice</th><th>Pembeli</th><th>Total</th><th>Pembayaran</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada pesanan</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $i => $o): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><a href="/admin/orders/<?= $o['id'] ?>" class="fw-600 text-decoration-none" style="color:var(--primary);"><?= esc($o['invoice_number']) ?></a></td>
                        <td>
                            <div class="fw-600 small"><?= esc($o['full_name']) ?></div>
                            <div class="text-muted" style="font-size:.75rem;"><?= esc($o['email']) ?></div>
                        </td>
                        <td class="fw-600">Rp <?= number_format($o['total_price'], 0, ',', '.') ?></td>
                        <td><span class="badge badge-<?= $o['payment_status'] ?> px-2 py-1"><?= ucfirst($o['payment_status']) ?></span></td>
                        <td><span class="badge badge-<?= $o['status'] ?> px-2 py-1"><?= ucfirst($o['status']) ?></span></td>
                        <td class="text-muted small"><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                        <td><a href="/admin/orders/<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
