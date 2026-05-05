<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Jajan Mranggen') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { 
            --primary: #73b57a;
            --primary-dark: #326338;
            --secondary: #4f9857; }
        * { font-family: 'Poppins', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #326338 0%, #4f9857 40%, #73b57a 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .auth-card {
            background: #fff; border-radius: 24px; padding: 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,.15);
            width: 100%; max-width: 460px;
        }
        .auth-logo { color: var(--primary); font-weight: 800; font-size: 1.8rem; text-align: center; }
        .auth-logo span { color: #1A1A2E; }
        .auth-title { font-size: 1.4rem; font-weight: 700; color: #1A1A2E; }
        .form-control {
            border-radius: 12px; border: 2px solid #f0f0f0; padding: 12px 16px;
            font-size: .95rem; transition: border-color .2s;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255,107,53,.1); }
        .form-label { font-weight: 600; font-size: .9rem; color: #444; }
        .btn-auth {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff; border: none; border-radius: 12px; padding: 13px;
            font-weight: 700; font-size: 1rem; width: 100%; transition: all .3s;
        }
        .btn-auth:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,107,53,.4); color: #fff; }
        .divider { text-align: center; position: relative; margin: 20px 0; color: #aaa; font-size: .85rem; }
        .divider::before, .divider::after {
            content: ''; position: absolute; top: 50%;
            width: 42%; height: 1px; background: #eee;
        }
        .divider::before { left: 0; } .divider::after { right: 0; }
        .alert { border-radius: 12px; border: none; font-size: .9rem; }
        .alert-danger  { background: #fdf2f2; color: #c0392b; }
        .alert-success { background: #eafaf1; color: #1e8449; }
        .input-group-text { border-radius: 0 12px 12px 0; border: 2px solid #f0f0f0; border-left: none; background: #fff; }
    </style>
</head>
<body>
    <div class="container px-3">
        <div class="auth-card mx-auto">
            <div class="auth-logo mb-1">
                <i class="fa-solid fa-bowl-food me-1"></i>Jajan<span>Mranggen</span>
            </div>
            <p class="text-center text-muted mb-4" style="font-size:.85rem;">Platform Jajanan Khas Mranggen</p>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session()->getFlashdata('errors') as $e): ?>
                        <div><i class="fas fa-times me-1"></i><?= esc($e) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
