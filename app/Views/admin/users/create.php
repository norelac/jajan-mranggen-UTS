<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0"><?= esc($title) ?></h4>
        <small class="text-muted">Isi form di bawah untuk menambah pengguna baru</small>
    </div>
    <a href="/admin/users" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="form-card">
    <form action="/admin/users/store" method="POST">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" value="<?= old('full_name') ?>" placeholder="Nama lengkap" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control" value="<?= old('username') ?>" placeholder="username (tanpa spasi)" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="email@example.com" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>" placeholder="08xxxxxxxxxx">
            </div>
            <div class="col-md-6">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin"   <?= old('role') === 'admin'   ? 'selected' : '' ?>>Admin</option>
                    <option value="penjual" <?= old('role') === 'penjual' ? 'selected' : '' ?>>Penjual</option>
                    <option value="pembeli" <?= old('role') === 'pembeli' ? 'selected' : '' ?>>Pembeli</option>
                </select>
            </div>
            <div class="col-md-12">
                <label class="form-label">Alamat</label>
                <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap"><?= old('address') ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" <?= old('is_active', '1') == '1' ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= old('is_active') == '0' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
        </div>
        <hr class="my-4">
        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary-custom px-4"><i class="fas fa-save me-2"></i>Simpan</button>
            <a href="/admin/users" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
