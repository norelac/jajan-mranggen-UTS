<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Detail Pesanan</h4>
        <small class="text-muted">Invoice: <strong><?= esc($order['invoice_number']) ?></strong></small>
    </div>
    <a href="/penjual/orders" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="table-card mb-4">
            <h6 class="fw-700 mb-3"><i class="fas fa-box me-2"></i>Item yang Harus Anda Siapkan</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        <?php $total = 0; foreach ($order['items'] as $item): $total += $item['subtotal']; ?>
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
                            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td><?= $item['quantity'] ?>x</td>
                            <td class="fw-600">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3" class="text-end fw-700">Total Pendapatan Anda:</td><td class="fw-700" style="color:var(--primary); font-size:1.1rem;">Rp <?= number_format($total, 0, ',', '.') ?></td></tr>
                    </tfoot>
                </table>
            </div>
            <?php if ($order['notes']): ?>
            <div class="p-3 rounded-3 mt-2" style="background:#FFF8F0;">
                <small class="fw-600">Catatan dari pembeli:</small>
                <p class="mb-0 small text-muted"><?= esc($order['notes']) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="table-card">
            <h6 class="fw-700 mb-3"><i class="fas fa-truck me-2"></i>Update Status Pengiriman</h6>
            <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
            <form action="/penjual/orders/updateStatus/<?= $order['id'] ?>" method="POST" class="d-flex gap-3 align-items-center">
                <?= csrf_field() ?>
                <select name="status" class="form-select w-50">
                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Sedang Diproses</option>
                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Siap Dikirim / Diserahkan ke Kurir</option>
                </select>
                <button type="submit" class="btn btn-primary-custom px-4"><i class="fas fa-save me-2"></i>Update</button>
            </form>
            <p class="text-muted small mt-2 mb-0">* Anda hanya dapat mengupdate status hingga barang dikirim. Pembeli akan mengkonfirmasi jika barang sudah diterima.</p>
            <?php else: ?>
                <div class="alert alert-info mb-0">Status pesanan saat ini: <strong><?= ucfirst($order['status']) ?></strong>. Anda tidak dapat mengubah status ini.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="table-card mb-4">
            <h6 class="fw-700 mb-3"><i class="fas fa-user me-2"></i>Info Pengiriman</h6>
            <p class="mb-1 fw-600"><?= esc($order['full_name']) ?></p>
            <p class="mb-1 small text-muted"><i class="fas fa-phone me-1"></i><?= esc($order['phone'] ?? '-') ?></p>
            <hr>
            <p class="mb-1 fw-600 small">Alamat:</p>
            <p class="mb-0 small text-muted"><?= esc($order['shipping_address']) ?></p>
        </div>
        <div class="table-card">
            <h6 class="fw-700 mb-3"><i class="fas fa-info-circle me-2"></i>Status Global</h6>
            <div class="d-flex justify-content-between mb-2"><span class="small text-muted">Order:</span><span class="badge badge-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></div>
            <div class="d-flex justify-content-between"><span class="small text-muted">Pembayaran:</span><span class="badge badge-<?= $order['payment_status'] ?>"><?= ucfirst($order['payment_status']) ?></span></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
