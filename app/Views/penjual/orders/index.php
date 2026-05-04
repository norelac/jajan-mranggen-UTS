<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Pesanan Masuk</h4>
        <small class="text-muted">Kelola pesanan yang harus Anda proses</small>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>Invoice</th><th>Pembeli</th><th>Item Anda</th><th>Subtotal</th><th>Status Order</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada pesanan masuk</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><a href="/penjual/orders/<?= $o['order_id'] ?>" class="fw-600 text-decoration-none" style="color:var(--primary);"><?= esc($o['invoice_number']) ?></a></td>
                        <td><?= esc($o['buyer_name']) ?></td>
                        <td>
                            <ul class="list-unstyled mb-0 small">
                                <?php foreach ($o['items'] as $item): ?>
                                    <li><?= $item['quantity'] ?>x <?= esc($item['product_name']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td class="fw-600">Rp <?= number_format($o['subtotal'], 0, ',', '.') ?></td>
                        <td><span class="badge badge-<?= $o['order_status'] ?>"><?= ucfirst($o['order_status']) ?></span></td>
                        <td class="text-muted small"><?= date('d M Y H:i', strtotime($o['order_date'])) ?></td>
                        <td><a href="/penjual/orders/<?= $o['order_id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Proses</a></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
