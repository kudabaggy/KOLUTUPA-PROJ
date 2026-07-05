<?php // app/views/auth/register.php ?>
<div class="auth-page">
    <div class="auth-box">
        <h1>Bikin akun di KOLUTUPA,<br><span>langsung bisa jual &amp; beli</span></h1>

        <p class="auth-sub">Sudah punya akun? <a href="<?= BASE_URL ?>index.php?page=login">Masuk</a></p>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $e): ?><p><?= $e ?></p><?php endforeach; ?>
        </div>
        <?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>index.php?action=register">
            <?= csrf() ?>
            <div class="form-group">
                <input type="text" name="name" placeholder="Nama lengkap" class = "form-control"
                       value="<?= sanitize($old['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" class = "form-control"
                       value="<?= sanitize($old['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" class = "form-control"
                       value="<?= sanitize($old['username'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" class = "form-control" required> 
            </div>
            <button type="submit" class="btn-primary btn-full">Daftar</button>
        </form>
    </div>
</div>
