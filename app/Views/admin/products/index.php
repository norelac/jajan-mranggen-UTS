<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Manajemen Produk</h4>
        <small class="text-muted">Kelola semua produk dari semua penjual</small>
    </div>
    <a href="/admin/products/create" class="btn btn-primary-custom"><i class="fas fa-plus me-2"></i>Tambah Produk</a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>#</th><th>Produk</th><th>Kategori</th><th>Penjual</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada produk</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $i => $p): ?>
                    <tr <?= $p['deleted_at'] ? 'class="opacity-50"' : '' ?>>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <?php if ($p['image'] && file_exists(FCPATH . $p['image'])): ?>
                                    <img src="/<?= esc($p['image']) ?>" width="45" height="45" class="rounded-3 object-fit-cover">
                                <?php else: ?>
                                    <div style="width:45px;height:45px;background:#FFE0D0;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-box" style="color:var(--primary);font-size:.9rem;"></i></div>
                                <?php endif; ?>
                                <div class="fw-600 small"><?= esc($p['name']) ?></div>
                            </div>
                        </td>
                        <td><span class="badge" style="background:#FFF8F0;color:var(--primary);"><?= esc($p['category_name']) ?></span></td>
                        <td class="small text-muted"><?= esc($p['seller_name']) ?></td>
                        <td class="fw-600" style="color:var(--primary);">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                        <td><?= $p['stock'] ?></td>
                        <td><span class="badge badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                        <td>
                            <?php if (! $p['deleted_at']): ?>
                            <a href="/admin/products/edit/<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                            <a href="/admin/products/delete/<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Hapus produk ini?')"><i class="fas fa-trash"></i></a>
                            <?php else: ?>
                                <span class="text-muted small">Terhapus</span>
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
