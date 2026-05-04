<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Detail Pesanan</h4>
        <small class="text-muted">Invoice: <strong><?= esc($order['invoice_number']) ?></strong></small>
    </div>
    <a href="/admin/orders" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="row g-4">
    <!-- Order Items -->
    <div class="col-lg-8">
        <div class="table-card">
            <h6 class="fw-700 mb-3"><i class="fas fa-box me-2"></i>Item Pesanan</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>Produk</th><th>Penjual</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
                    </thead>
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
                        <tr class="table-light">
                            <td colspan="4" class="text-end fw-600">Subtotal Produk:</td>
                            <td class="fw-600">Rp <?= number_format($order['total_price'] - $order['shipping_cost'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="4" class="text-end fw-600">Ongkir:</td>
                            <td class="fw-600">Rp <?= number_format($order['shipping_cost'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-700" style="color:var(--primary);">Total:</td>
                            <td class="fw-700" style="color:var(--primary); font-size:1.1rem;">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php if ($order['notes']): ?>
            <div class="p-3 rounded-3 mt-2" style="background:#FFF8F0;">
                <small class="fw-600">Catatan:</small>
                <p class="mb-0 small text-muted"><?= esc($order['notes']) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Order Info & Update -->
    <div class="col-lg-4">
        <!-- Buyer Info -->
        <div class="table-card mb-4">
            <h6 class="fw-700 mb-3"><i class="fas fa-user me-2"></i>Info Pembeli</h6>
            <p class="mb-1 fw-600"><?= esc($order['full_name']) ?></p>
            <p class="mb-1 small text-muted"><i class="fas fa-envelope me-1"></i><?= esc($order['email']) ?></p>
            <p class="mb-1 small text-muted"><i class="fas fa-phone me-1"></i><?= esc($order['phone'] ?? '-') ?></p>
            <hr>
            <p class="mb-1 fw-600 small">Alamat Pengiriman:</p>
            <p class="mb-0 small text-muted"><?= esc($order['shipping_address']) ?></p>
        </div>

        <!-- Payment Info -->
        <div class="table-card mb-4">
            <h6 class="fw-700 mb-3"><i class="fas fa-credit-card me-2"></i>Info Pembayaran</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="small text-muted">Metode:</span>
                <span class="fw-600 small"><?= str_replace('_', ' ', strtoupper($order['payment_method'])) ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="small text-muted">Status Bayar:</span>
                <span class="badge badge-<?= $order['payment_status'] ?>"><?= ucfirst($order['payment_status']) ?></span>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <span class="small text-muted">Berat Total:</span>
                <span class="fw-600 small"><?= $order['total_weight'] ?> gram</span>
            </div>
        </div>

        <!-- Update Status -->
        <div class="table-card">
            <h6 class="fw-700 mb-3"><i class="fas fa-edit me-2"></i>Update Status</h6>
            <form action="/admin/orders/updateStatus/<?= $order['id'] ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-600">Status Pesanan</label>
                    <select name="status" class="form-select form-select-sm">
                        <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                            <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-600">Status Pembayaran</label>
                    <select name="payment_status" class="form-select form-select-sm">
                        <?php foreach (['pending','paid','failed'] as $s): ?>
                            <option value="<?= $s ?>" <?= $order['payment_status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100 btn-sm"><i class="fas fa-save me-2"></i>Perbarui Status</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
