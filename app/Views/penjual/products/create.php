<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-700 mb-0"><?= esc($title) ?></h4></div>
    <a href="/penjual/products" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="form-card">
    <form action="/penjual/products/store" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="<?= old('name') ?>" placeholder="Nama produk Anda" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active"   <?= old('status', 'active') === 'active'   ? 'selected' : '' ?>>Aktif (tampil di katalog)</option>
                    <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Nonaktif (disembunyikan)</option>
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
                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" value="<?= old('price') ?>" placeholder="Contoh: 25000" min="1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stock" class="form-control" value="<?= old('stock', 0) ?>" min="0" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Berat (gram) <span class="text-danger">*</span></label>
                <input type="number" name="weight" class="form-control" value="<?= old('weight') ?>" placeholder="500" min="1" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Foto Produk <small class="text-muted">(max 2MB, jpg/png/webp)</small></label>
                <input type="file" name="image" class="form-control" accept="image/*" id="imgInput">
                <img id="previewImg" src="" class="mt-2 rounded-3 d-none" style="height:80px; object-fit:cover;">
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi Produk</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Ceritakan produk Anda: bahan, rasa, cara penyajian, dll."><?= old('description') ?></textarea>
            </div>
        </div>
        <hr class="my-4">
        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary-custom px-4"><i class="fas fa-save me-2"></i>Simpan Produk</button>
            <a href="/penjual/products" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->section('scripts') ?>
<script>
document.getElementById('imgInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => { const p = document.getElementById('previewImg'); p.src = e.target.result; p.classList.remove('d-none'); };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
