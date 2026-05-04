<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-700 mb-0"><?= esc($title) ?></h4></div>
    <a href="/admin/categories" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="form-card">
    <form action="/admin/categories/store" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="<?= old('name') ?>" placeholder="Nama kategori" required id="nameInput">
            </div>
            <div class="col-md-6">
                <label class="form-label">Gambar <small class="text-muted">(max 2MB, jpg/png/webp)</small></label>
                <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                <div id="imgPreview" class="mt-2 d-none">
                    <img id="previewImg" src="" alt="Preview" class="rounded-3" style="height:80px; object-fit:cover;">
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi kategori..."><?= old('description') ?></textarea>
            </div>
        </div>
        <hr class="my-4">
        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary-custom px-4"><i class="fas fa-save me-2"></i>Simpan</button>
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
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imgPreview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
