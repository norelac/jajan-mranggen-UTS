<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-700 mb-0"><?= esc($title) ?></h4></div>
    <a href="/admin/products" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="form-card">
    <form action="/admin/products/store" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="<?= old('name') ?>" placeholder="Nama produk" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active"   <?= old('status', 'active') === 'active'   ? 'selected' : '' ?>>Aktif</option>
                    <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Penjual <span class="text-danger">*</span></label>
                <select name="seller_id" class="form-select" required>
                    <option value="">-- Pilih Penjual --</option>
                    <?php foreach ($sellers as $seller): ?>
                        <option value="<?= $seller['id'] ?>" <?= old('seller_id') == $seller['id'] ? 'selected' : '' ?>><?= esc($seller['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Gambar <small class="text-muted">(max 2MB)</small></label>
                <input type="file" name="image" class="form-control" accept="image/*" id="imgInput">
                <img id="previewImg" src="" class="mt-2 rounded-3 d-none" style="height:70px; object-fit:cover;">
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" value="<?= old('price') ?>" placeholder="0" min="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stock" class="form-control" value="<?= old('stock', 0) ?>" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Berat (gram) <span class="text-danger">*</span></label>
                <input type="number" name="weight" class="form-control" value="<?= old('weight') ?>" placeholder="0" min="1" required>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Deskripsi produk..."><?= old('description') ?></textarea>
            </div>
        </div>
        <hr class="my-4">
        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary-custom px-4"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="/admin/products" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->section('scripts') ?>
<script>
document.getElementById('imgInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            const p = document.getElementById('previewImg');
            p.src = e.target.result; p.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
