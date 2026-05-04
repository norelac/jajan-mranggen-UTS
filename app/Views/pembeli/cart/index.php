<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-700 mb-0"><?= esc($title) ?></h4></div>
</div>

<?php if (empty($cart)): ?>
    <div class="table-card text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-3 opacity-25"></i>
        <h5 class="fw-700">Keranjang Belanja Kosong</h5>
        <p class="text-muted mb-4">Anda belum memasukkan produk apapun ke keranjang.</p>
        <a href="/produk" class="btn btn-primary-custom px-4 rounded-pill">Mulai Belanja</a>
    </div>
<?php else: ?>
    <div class="row g-4">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="table-card mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-700 mb-0">Item Belanja (<?= count($cart) ?> produk)</h6>
                    <a href="/pembeli/cart/clear" class="btn btn-sm btn-outline-danger" onclick="return confirm('Kosongkan keranjang?')">Kosongkan</a>
                </div>
                
                <?php $totalWeight = 0; ?>
                <?php foreach ($cart as $id => $item): ?>
                <?php $totalWeight += ($item['weight'] * $item['qty']); ?>
                <div class="d-flex align-items-center gap-3 p-3 border rounded-3 mb-3">
                    <?php if ($item['image'] && file_exists(FCPATH . $item['image'])): ?>
                        <img src="/<?= esc($item['image']) ?>" width="80" height="80" class="rounded-3 object-fit-cover">
                    <?php else: ?>
                        <div style="width:80px;height:80px;background:#FFE0D0;border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-bowl-food" style="color:var(--primary);font-size:2rem;"></i></div>
                    <?php endif; ?>
                    
                    <div class="flex-grow-1">
                        <h6 class="fw-600 mb-1"><?= esc($item['name']) ?></h6>
                        <div class="fw-700" style="color:var(--primary);">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                    </div>
                    
                    <div>
                        <form action="/pembeli/cart/update" method="POST" class="d-flex align-items-center border rounded-3 overflow-hidden" style="width:120px;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= $id ?>">
                            <button type="button" class="btn border-0 px-2" onclick="this.nextElementSibling.stepDown(); this.form.submit();">-</button>
                            <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" max="<?= $item['stock'] ?>" class="form-control border-0 text-center px-0" style="border-radius:0;" onchange="this.form.submit()">
                            <button type="button" class="btn border-0 px-2" onclick="this.previousElementSibling.stepUp(); this.form.submit();">+</button>
                        </form>
                    </div>
                    
                    <div class="text-end" style="min-width:100px;">
                        <div class="fw-700 mb-2">Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></div>
                        <a href="/pembeli/cart/remove/<?= $id ?>" class="text-danger small text-decoration-none"><i class="fas fa-trash me-1"></i>Hapus</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="/produk" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Lanjut Belanja</a>
        </div>

        <!-- Checkout Form -->
        <div class="col-lg-4">
            <div class="table-card sticky-top" style="top:90px;">
                <h6 class="fw-700 mb-3">Ringkasan Belanja</h6>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Harga (<?= count($cart) ?> barang)</span>
                    <span class="fw-600">Rp <?= number_format($total, 0, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <span class="text-muted">Total Berat</span>
                    <span class="fw-600"><?= number_format($totalWeight, 0, ',', '.') ?> gram</span>
                </div>
                
                <form action="/pembeli/orders/checkout" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-600">Alamat Pengiriman <span class="text-danger">*</span></label>
                        <textarea name="shipping_address" class="form-control" rows="3" placeholder="Alamat lengkap dengan RT/RW, Desa, Kecamatan, Kodepos..." required><?= esc(session()->get('userAddress') ?? '') ?></textarea>
                        <div class="form-text" style="font-size:.7rem;">Ongkir akan dihitung otomatis berdasarkan berat pesanan.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-600">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="transfer_bank">Transfer Bank</option>
                            <option value="dompet_digital">Dompet Digital (OVO/Dana/Gopay)</option>
                            <option value="cod">Cash on Delivery (COD)</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-600">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Pesan untuk penjual..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fs-6 rounded-pill">Buat Pesanan</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
