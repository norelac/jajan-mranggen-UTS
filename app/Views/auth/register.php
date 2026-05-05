<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<h2 class="auth-title text-center mb-1">Buat Akun Baru</h2>
<p class="text-center text-muted mb-4" style="font-size:.9rem;">Bergabung dan mulai berjualan atau berbelanja</p>

<form action="/auth/register" method="POST" id="formRegister">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="full_name" class="form-label">Nama Lengkap</label>
        <input type="text" id="full_name" name="full_name" class="form-control"
               placeholder="Nama lengkap Anda" value="<?= old('full_name') ?>" required>
    </div>

    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control"
                   placeholder="username" value="<?= old('username') ?>" required>
        <!-- <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control"
                   placeholder="username" value="<?= old('username') ?>" required>
        </div> -->
        <!-- <div class="col-6">
            <label for="phone" class="form-label">No. HP</label>
            <input type="text" id="phone" name="phone" class="form-control"
                   placeholder="08xx..." value="<?= old('phone') ?>">
        </div> -->
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control"
               placeholder="contoh@email.com" value="<?= old('email') ?>" required>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Alamat</label>
        <textarea id="address" name="address" class="form-control" rows="2"
                  placeholder="Alamat lengkap Anda"><?= old('address') ?></textarea>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Min. 6 karakter" required>
        </div>
        <div class="col-6">
            <label for="confirm_password" class="form-label">Konfirmasi</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                   placeholder="Ulangi password" required>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Daftar sebagai</label>
        <div class="row g-2">
            <div class="col-6">
                <input type="radio" class="btn-check" name="role" id="role_pembeli" value="pembeli"
                       <?= old('role', 'pembeli') === 'pembeli' ? 'checked' : '' ?>>
                <label class="btn btn-outline-secondary w-100 rounded-3" for="role_pembeli">
                    <i class="fas fa-shopping-cart me-1"></i>Pembeli
                </label>
            </div>
            <div class="col-6">
                <input type="radio" class="btn-check" name="role" id="role_penjual" value="penjual"
                       <?= old('role') === 'penjual' ? 'checked' : '' ?>>
                <label class="btn btn-outline-secondary w-100 rounded-3" for="role_penjual">
                    <i class="fas fa-store me-1"></i>Penjual
                </label>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-auth mb-3">
        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
    </button>
</form>

<p class="text-center mt-2" style="font-size:.9rem; color:#666;">
    Sudah punya akun? <a href="/auth/login" style="color:var(--primary); font-weight:600;">Masuk</a>
</p>

<?= $this->endSection() ?>
