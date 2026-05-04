<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-700 mb-0"><?= esc($title) ?></h4></div>
    <a href="/admin/categories" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="form-card">
    <form action="/admin/categories/update/<?= $category['id'] ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="<?= old('name', $category['name']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Gambar Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                <?php if ($category['image']): ?>
                    <div class="mt-2">
                        <img src="/<?= esc($category['image']) ?>" alt="Current" class="rounded-3" style="height:70px; object-fit:cover;">
                        <small class="text-muted d-block mt-1">Gambar saat ini</small>
                    </div>
                <?php endif; ?>
                <img id="previewImg" src="" alt="Preview" class="rounded-3 mt-2 d-none" style="height:70px; object-fit:cover;">
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3"><?= old('description', $category['description']) ?></textarea>
            </div>
        </div>
        <hr class="my-4">
        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary-custom px-4"><i class="fas fa-save me-2"></i>Perbarui</button>
            <a href="/admin/categories" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->section('scripts') ?>
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const prev = document.getElementById('previewImg');
            prev.src = e.target.result;
            prev.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
