<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Profil Saya</h4>
        <small class="text-muted">Kelola informasi profil dan alamat pengiriman Anda</small>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="form-card">
            <form action="/pembeli/profile/update" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="text-center mb-4 pb-4 border-bottom">
                    <div class="position-relative d-inline-block mb-3">
                        <?php if ($user['avatar'] && file_exists(FCPATH . $user['avatar'])): ?>
                            <img src="/<?= esc($user['avatar']) ?>" id="previewAvatar" class="rounded-circle object-fit-cover" style="width:120px;height:120px;border:4px solid #fff;box-shadow:0 5px 15px rgba(0,0,0,.1);">
                        <?php else: ?>
                            <div id="previewAvatar" class="rounded-circle d-flex align-items-center justify-content-center" style="width:120px;height:120px;background:var(--primary);color:#fff;font-size:3rem;font-weight:700;border:4px solid #fff;box-shadow:0 5px 15px rgba(0,0,0,.1);">
                                <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        
                        <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;box-shadow:0 2px 10px rgba(0,0,0,.1);cursor:pointer;color:var(--primary);">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*">
                    </div>
                    <h5 class="fw-700 mb-1"><?= esc($user['full_name']) ?></h5>
                    <p class="text-muted small mb-0"><?= esc($user['email']) ?></p>
                    <span class="badge rounded-pill mt-2" style="background:rgba(255,107,53,.15); color:var(--primary);">Pembeli</span>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" value="<?= old('full_name', $user['full_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username <small class="text-muted">(Tidak dapat diubah)</small></label>
                        <input type="text" class="form-control bg-light" value="<?= esc($user['username']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <small class="text-muted">(Tidak dapat diubah)</small></label>
                        <input type="email" class="form-control bg-light" value="<?= esc($user['email']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" value="<?= old('phone', $user['phone']) ?>" placeholder="08xx...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Alamat lengkap untuk pengiriman..."><?= old('address', $user['address']) ?></textarea>
                        <div class="form-text small">Alamat ini akan digunakan sebagai alamat default saat checkout.</div>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <h6 class="fw-700 border-bottom pb-2 mb-3">Keamanan</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                    </div>
                </div>
                
                <hr class="my-4">
                <div class="text-end">
                    <button type="submit" class="btn btn-primary-custom px-5"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('previewAvatar');
            if(preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Change div to img
                const img = document.createElement('img');
                img.id = 'previewAvatar';
                img.src = e.target.result;
                img.className = 'rounded-circle object-fit-cover';
                img.style.cssText = preview.style.cssText;
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
