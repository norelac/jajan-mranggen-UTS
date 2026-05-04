<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Manajemen Kategori</h4>
        <small class="text-muted">Kelola kategori produk</small>
    </div>
    <a href="/admin/categories/create" class="btn btn-primary-custom"><i class="fas fa-plus me-2"></i>Tambah Kategori</a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>#</th><th>Gambar</th><th>Nama</th><th>Slug</th><th>Deskripsi</th><th>Produk</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada kategori</td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $i => $cat): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?php if ($cat['image'] && file_exists(FCPATH . $cat['image'])): ?>
                                <img src="/<?= esc($cat['image']) ?>" width="50" height="50" class="rounded-3 object-fit-cover">
                            <?php else: ?>
                                <div style="width:50px;height:50px;background:#FFE0D0;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-tag" style="color:var(--primary);"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-600"><?= esc($cat['name']) ?></td>
                        <td><code><?= esc($cat['slug']) ?></code></td>
                        <td class="text-muted small"><?= esc(substr($cat['description'] ?? '-', 0, 60)) ?>...</td>
                        <td><span class="badge badge-active"><?= $cat['product_count'] ?? 0 ?> produk</span></td>
                        <td>
                            <a href="/admin/categories/edit/<?= $cat['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                            <a href="/admin/categories/delete/<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Hapus kategori <?= esc($cat['name']) ?>?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
