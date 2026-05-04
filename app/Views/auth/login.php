<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<h2 class="auth-title text-center mb-1">Selamat Datang!</h2>
<p class="text-center text-muted mb-4" style="font-size:.9rem;">Masuk ke akun Jajan Mranggen Anda</p>

<form action="/auth/login" method="POST" id="formLogin">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control"
               placeholder="contoh@email.com" value="<?= old('email') ?>" required>
    </div>

    <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Masukkan password" required style="border-right:none;">
            <button type="button" class="btn btn-outline-secondary" id="togglePwd"
                    style="border-radius:0 12px 12px 0; border:2px solid #f0f0f0; border-left:none;">
                <i class="fas fa-eye" id="eyeIcon"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-auth mb-3">
        <i class="fas fa-right-to-bracket me-2"></i>Masuk Sekarang
    </button>
</form>

<div class="divider">atau</div>

<div class="text-center mt-3">
    <p class="mb-1" style="font-size:.9rem; color:#666;">Belum punya akun?
        <a href="/auth/register" style="color:var(--primary); font-weight:600;">Daftar sekarang</a>
    </p>
</div>

<!-- <div class="mt-4 p-3 rounded-3" style="background:#FFF8F0; font-size:.8rem;">
    <p class="mb-1 fw-600"><i class="fas fa-info-circle me-1" style="color:var(--primary)"></i><strong>Demo Akun:</strong></p>
    <p class="mb-0">🔴 Admin: <code>admin@jajanmranggen.com</code> / <code>admin123</code></p>
    <p class="mb-0">🟠 Penjual: <code>sari@jajanmranggen.com</code> / <code>penjual123</code></p>
    <p class="mb-0">🟢 Pembeli: <code>dewi@gmail.com</code> / <code>pembeli123</code></p>
</div> -->

<script>
document.getElementById('togglePwd').addEventListener('click', function() {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
});
</script>

<?= $this->endSection() ?>
