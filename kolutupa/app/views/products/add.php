<?php // app/views/products/add.php ?>
<div class="container form-page">
    <h1>Tambah produk</h1>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $e): ?><p><?= $e ?></p><?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>index.php?action=add-product" enctype="multipart/form-data">
        <?= csrf() ?>

        <!-- Photos -->
        <section class="form-section">
            <h3>Foto</h3>
            <div class="photo-upload-grid" id="photoGrid">
                <label class="photo-slot active" id="addPhotoBtn">
                    <input type="file" name="images[]" multiple accept="image/*" id="photoInput" style="display:none">
                    <span class="photo-icon">📷</span>
                    <span>Tambah foto</span>
                </label>
                <?php for ($i = 0; $i < 7; $i++): ?>
                <div class="photo-slot empty"></div>
                <?php endfor; ?>
            </div>
            <div id="photoPreview" class="photo-preview-list"></div>
        </section>

        <!-- Title -->
        <section class="form-section">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" placeholder="Nama produk"
                   value="<?= sanitize($_POST['title'] ?? '') ?>" required>
        </section>

        <!-- Description -->
        <section class="form-section">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="5"
                      placeholder="Deskripsikan produkmu..."><?= sanitize($_POST['description'] ?? '') ?></textarea>
        </section>

        <!-- Detail -->
        <section class="form-section">
            <h3>Detail</h3>
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-control" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach (['Pria','Wanita','Branded','Sale'] as $cat): ?>
                    <option value="<?= $cat ?>" <?= ($_POST['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat === 'Sale' ? 'Negotiable' : $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kondisi</label>
                <select name="condition_item" class="form-control" required>
                    <option value="">Pilih kondisi</option>
                    <?php foreach (['Sangat baik','Baik','Cukup','Kurang'] as $c): ?>
                    <option value="<?= $c ?>" <?= ($_POST['condition_item'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ukuran</label>
                    <select name="size" class="form-control">
                        <option value="">Pilih ukuran</option>
                        <?php foreach (['XS','S','M','L','XL','XXL','XXXL'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($_POST['size'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" placeholder="Brand"
                           value="<?= sanitize($_POST['brand'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Warna</label>
                <input type="text" name="color" class="form-control" placeholder="cth: Black, White"
                       value="<?= sanitize($_POST['color'] ?? '') ?>">
            </div>

            <!-- Measurements -->
            <div class="form-group">
                <label class="form-label">Measurements (opsional)</label>
                <div id="measurements">
                    <div class="meas-row">
                        <input type="text" name="meas_label[]" class="form-control" placeholder="Label (cth: Panjang)">
                        <input type="text" name="meas_value[]" class="form-control" placeholder="Nilai (cth: 70cm)">
                        <button type="button" onclick="removeMeas(this)" class="btn-ghost">✕</button>
                    </div>
                </div>
                <button type="button" onclick="addMeas()" class="btn-outline btn-sm">+ Tambah measurement</button>
            </div>
        </section>

        <!-- Price -->
        <section class="form-section">
            <label class="form-label">Harga (Rp)</label>
            <input type="number" name="price" class="form-control" placeholder="0"
                   value="<?= (int)($_POST['price'] ?? 0) ?: '' ?>" min="1000" required>
            <label class="checkbox-label">
                <input type="checkbox" name="negotiable" value="1" <?= !isset($_POST['negotiable']) || $_POST['negotiable'] ? 'checked' : '' ?>>
                Bisa ditawar
            </label>
        </section>

        <div class="form-actions">
            <!-- <button type="submit" name="draft" value="1" class="btn-outline">Save draft</button> -->
            <button type="submit" class="btn-primary">Upload</button>
        </div>
    </form>
</div>

<script>
document.getElementById('addPhotoBtn').addEventListener('click', () => document.getElementById('photoInput').click());
document.getElementById('photoInput').addEventListener('change', function() {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-thumb';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
function addMeas() {
    const div = document.createElement('div');
    div.className = 'meas-row';
    div.innerHTML = '<input type="text" name="meas_label[]" class="form-control" placeholder="Label"><input type="text" name="meas_value[]" class="form-control" placeholder="Nilai"><button type="button" onclick="removeMeas(this)" class="btn-ghost">✕</button>';
    document.getElementById('measurements').appendChild(div);
}
function removeMeas(btn) { btn.parentElement.remove(); }
</script>
