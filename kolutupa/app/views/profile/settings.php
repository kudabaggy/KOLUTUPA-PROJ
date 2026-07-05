<?php // app/views/profile/settings.php ?>
<div class="settings-page container">
    <div class="settings-layout">
        <!-- Sidebar -->
        <aside class="settings-sidebar">
            <a href="?page=settings&tab=profile" class="settings-nav-item <?= $tab === 'profile' ? 'active' : '' ?>">
                <span>👤</span> Profil
            </a>
            <a href="?page=settings&tab=akun" class="settings-nav-item <?= $tab === 'akun' ? 'active' : '' ?>">
                <span>⚙️</span> Akun
            </a>
            <a href="?page=settings&tab=alamat" class="settings-nav-item <?= $tab === 'alamat' ? 'active' : '' ?>">
                <span>📍</span> Alamat Penjual
            </a>
        </aside>

        <!-- Content -->
        <div class="settings-content">
            <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?><p><?= $e ?></p><?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($tab === 'profile'): ?>
            <!-- Profile Tab -->
            <div class="settings-panel">
                <h2>Profil</h2>
                <p class="settings-sub">Atur dan perbarui profil kamu disini</p>
                <form method="POST" action="<?= BASE_URL ?>index.php?action=update-profile" enctype="multipart/form-data">
                    <?= csrf() ?>
                    <div class="avatar-section">
                        <img src="<?= avatarUrl($user['avatar']) ?>" alt="" id="avatarPreview" class="settings-avatar">
                        <div class="avatar-actions">
                            <label class="btn-primary" style="cursor:pointer">
                                Ganti gambar
                                <input type="file" name="avatar" accept="image/*" style="display:none" onchange="previewAvatar(this)">
                            </label>
                            <button type="button" class="btn-ghost" onclick="document.getElementById('avatarPreview').src='<?= BASE_URL ?>assets/images/default-avatar.png'">Hapus</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control"
                               value="<?= sanitize($user['name']) ?>" placeholder="Nama penerima" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="4"
                                  placeholder="Perkenalkan dirimu dan Tokomu ke semua orang!"><?= sanitize($user['bio'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Website</label>
                        <input type="text" name="website" class="form-control"
                               value="<?= sanitize($user['website'] ?? '') ?>" placeholder="www.tokomu.com">
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>

            <?php elseif ($tab === 'akun'): ?>
            <!-- Account Tab -->
            <div class="settings-panel">
                <h2>Pengaturan Akun</h2>
                <p class="settings-sub">Disini kamu bisa mengatur info Akunmu. kamu bisa megatur username, email, dan password kamu disini</p>
                <form method="POST" action="<?= BASE_URL ?>index.php?action=update-account">
                    <?= csrf() ?>
                    <div class="form-group form-inline">
                        <label class="form-label">Email</label>
                        <div class="input-with-action">
                            <input type="email" name="email" class="form-control"
                                   value="<?= sanitize($user['email']) ?>" required>
                            <button type="button" class="btn-link">Ubah</button>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label class="form-label">Password</label>
                        <div class="input-with-action">
                            <input type="password" name="new_password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                            <button type="button" class="btn-link">Ubah</button>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label class="form-label">Username</label>
                        <div class="input-with-action">
                            <input type="text" name="username" class="form-control"
                                   value="<?= sanitize($user['username']) ?>" required>
                            <button type="button" class="btn-link">Ubah</button>
                        </div>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>

            <?php else: ?>
            <!-- Address Tab -->
            <div class="settings-panel">
                <h2>Alamat Penjual</h2>
                <p class="settings-sub">Pastikan alamat selalu terbaru.</p>
                <?php if ($address): ?>
                <div class="address-card">
                    <div class="address-info">
                        <strong><?= sanitize($address['recipient_name']) ?></strong>
                        <p><?= sanitize($address['phone']) ?></p>
                        <p><?= sanitize($address['full_address']) ?>, <?= sanitize($address['city']) ?>, <?= sanitize($address['province']) ?> <?= sanitize($address['postal_code']) ?></p>
                    </div>
                    <button class="btn-ghost" onclick="document.getElementById('editAddress').style.display='block'">✏️</button>
                </div>
                <?php endif; ?>
                <div id="editAddress" style="<?= $address ? 'display:none' : '' ?>">
                    <form method="POST" action="<?= BASE_URL ?>index.php?action=update-address">
                        <?= csrf() ?>
                        <div class="form-group">
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" name="recipient_name" class="form-control"
                                   value="<?= sanitize($address['recipient_name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control"
                                   value="<?= sanitize($address['phone'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="full_address" class="form-control" rows="3" required><?= sanitize($address['full_address'] ?? '') ?></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Kota</label>
                                <input type="text" name="city" class="form-control"
                                       value="<?= sanitize($address['city'] ?? '') ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Provinsi</label>
                                <input type="text" name="province" class="form-control"
                                       value="<?= sanitize($address['province'] ?? '') ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" name="postal_code" class="form-control"
                                       value="<?= sanitize($address['postal_code'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
