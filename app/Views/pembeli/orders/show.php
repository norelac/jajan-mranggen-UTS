<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Detail Pesanan</h4>
        <small class="text-muted">Invoice: <strong><?= esc($order['invoice_number']) ?></strong></small>
    </div>
    <a href="/pembeli/orders" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="table-card mb-4">
            <h6 class="fw-700 mb-3"><i class="fas fa-box me-2"></i>Daftar Item</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Produk</th><th>Penjual</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($item['product_image'] && file_exists(FCPATH . $item['product_image'])): ?>
                                        <img src="/<?= esc($item['product_image']) ?>" width="40" height="40" class="rounded-3 object-fit-cover">
                                    <?php else: ?>
                                        <div style="width:40px;height:40px;background:#FFE0D0;border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-bowl-food" style="color:var(--primary);font-size:.8rem;"></i></div>
                                    <?php endif; ?>
                                    <span class="fw-600 small"><?= esc($item['product_name']) ?></span>
                                </div>
                            </td>
                            <td class="small text-muted"><?= esc($item['seller_name']) ?></td>
                            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td><?= $item['quantity'] ?>x</td>
                            <td class="fw-600">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light"><td colspan="4" class="text-end fw-600">Subtotal Produk:</td><td class="fw-600">Rp <?= number_format($order['total_price'] - $order['shipping_cost'], 0, ',', '.') ?></td></tr>
                        <tr class="table-light"><td colspan="4" class="text-end fw-600">Ongkir:</td><td class="fw-600">Rp <?= number_format($order['shipping_cost'], 0, ',', '.') ?></td></tr>
                        <tr><td colspan="4" class="text-end fw-700" style="color:var(--primary);">Total Pembayaran:</td><td class="fw-700" style="color:var(--primary); font-size:1.1rem;">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td></tr>
                    </tfoot>
                </table>
            </div>
            
            <?php if ($order['notes']): ?>
            <div class="p-3 rounded-3 mt-2" style="background:#FFF8F0;">
                <small class="fw-600">Catatan Pesanan:</small>
                <p class="mb-0 small text-muted"><?= esc($order['notes']) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Status Panel -->
        <div class="table-card mb-4" style="<?= $order['status'] == 'cancelled' ? 'border:2px solid #f8d7da; background:#fff5f5;' : 'border:2px solid var(--primary); background:#fffefd;' ?>">
            <h6 class="fw-700 mb-3"><i class="fas fa-info-circle me-2"></i>Status Pesanan</h6>
            <h3 class="mb-1 fw-800" style="<?= $order['status'] == 'cancelled' ? 'color:#dc3545;' : 'color:var(--primary);' ?>"><?= ucfirst($order['status']) ?></h3>
            <p class="small text-muted mb-0">Diperbarui: <?= date('d M Y H:i', strtotime($order['updated_at'])) ?></p>
            
            <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                <form action="/pembeli/orders/cancel/<?= $order['id'] ?>" method="POST" class="mt-3">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">Batalkan Pesanan</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Info Alamat -->
        <div class="table-card mb-4">
            <h6 class="fw-700 mb-3"><i class="fas fa-map-marker-alt me-2"></i>Alamat Pengiriman</h6>
            <p class="mb-1 fw-600"><?= esc($order['full_name']) ?></p>
            <p class="mb-1 small text-muted"><i class="fas fa-phone me-1"></i><?= esc($order['phone'] ?? '-') ?></p>
            <p class="mb-0 small text-muted mt-2 border-top pt-2"><?= esc($order['shipping_address']) ?></p>
        </div>

        <!-- Info Pembayaran -->
        <div class="table-card">
            <h6 class="fw-700 mb-3"><i class="fas fa-credit-card me-2"></i>Info Pembayaran</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="small text-muted">Metode:</span>
                <span class="fw-600 small"><?= str_replace('_', ' ', strtoupper($order['payment_method'])) ?></span>
            </div>
            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                <span class="small text-muted">Status:</span>
                <span class="badge badge-<?= $order['payment_status'] ?>"><?= ucfirst($order['payment_status']) ?></span>
            </div>
            <?php if ($order['payment_status'] == 'pending'): ?>
                <div class="alert alert-warning small mb-0 mt-2 py-2">
                    <i class="fas fa-exclamation-triangle me-1"></i>Segera lakukan pembayaran agar pesanan dapat diproses.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
