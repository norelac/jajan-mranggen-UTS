<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-700 mb-0">Manajemen Pengguna</h4>
        <small class="text-muted">Kelola semua akun pengguna sistem</small>
    </div>
    <a href="/admin/users/create" class="btn btn-primary-custom"><i class="fas fa-plus me-2"></i>Tambah Pengguna</a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data pengguna</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $i => $user): ?>
                    <tr <?= $user['deleted_at'] ? 'class="table-secondary opacity-50"' : '' ?>>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:35px;height:35px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;">
                                    <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-600 small"><?= esc($user['full_name']) ?></div>
                                    <div class="text-muted" style="font-size:.75rem;"><?= esc($user['phone'] ?? '-') ?></div>
                                </div>
                            </div>
                        </td>
                        <td><code><?= esc($user['username']) ?></code></td>
                        <td><?= esc($user['email']) ?></td>
                        <td>
                            <?php
                            $roleColor = match($user['role']) { 'admin' => '#dc3545', 'penjual' => '#FF6B35', default => '#28a745' };
                            ?>
                            <span class="badge rounded-pill px-3" style="background:<?= $roleColor ?>22; color:<?= $roleColor ?>;">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td><span class="badge badge-<?= $user['is_active'] ? 'active' : 'inactive' ?>"><?= $user['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                        <td>
                            <?php if (! $user['deleted_at']): ?>
                            <a href="/admin/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                            <?php if ($user['id'] != session()->get('userId')): ?>
                            <a href="/admin/users/delete/<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Hapus pengguna <?= esc($user['full_name']) ?>?')">
                               <i class="fas fa-trash"></i>
                            </a>
                            <?php endif; ?>
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
