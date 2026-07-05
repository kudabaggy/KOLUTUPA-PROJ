<?php // app/views/auth/login.php ?>
<div class="auth-page">
    <div class="auth-box">
        <h1>Masuk akun KOLUTUPA<br><span>buat jual &amp; beli barang</span></h1>

        <p class="auth-sub">Belum punya akun? <a href="<?= BASE_URL ?>index.php?page=register">Daftar</a></p>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $e): ?><p><?= $e ?></p><?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?action=login">
            <?= csrf() ?>
            <div class="form-group">
                <input type="text" name="identifier" placeholder="Username atau email" class = "form-control"
                       value="<?= sanitize($_POST['identifier'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" class = "form-control" required>
            </div>
            <button type="submit" class="btn-primary btn-full">Login</button>
        </form>
    </div>
</div>
