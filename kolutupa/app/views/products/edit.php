<?php // app/views/products/edit.php ?>
<div class="container form-page">
    <h1>Edit produk</h1>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $e): ?><p><?= $e ?></p><?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>index.php?action=update-product" enctype="multipart/form-data">
        <?= csrf() ?>
        <input type="hidden" name="product_id" value="<?= (int)($product['id'] ?? 0) ?>">

        <!-- Photos -->
        <section class="form-section">
            <h3>Foto</h3>
            <?php if (!empty($images)): ?>
            <div class="existing-images">
                <?php foreach ($images as $img): ?>
                <img src="<?= productImageUrl($img['image_path']) ?>" alt="" class="existing-thumb">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="photo-upload-grid" id="photoGrid">
                <label class="photo-slot active" id="addPhotoBtn">
                    <input type="file" name="images[]" multiple accept="image/*" id="photoInput" style="display:none">
                    <span class="photo-icon">📷</span>
                    <span>Tambah foto baru</span>
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
                   value="<?= sanitize($_POST['title'] ?? ($product['title'] ?? '')) ?>" required>
        </section>

        <!-- Description -->
        <section class="form-section">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="5"
                      placeholder="Deskripsikan produkmu..."><?= sanitize($_POST['description'] ?? ($product['description'] ?? '')) ?></textarea>
        </section>

        <!-- Detail -->
        <section class="form-section">
            <h3>Detail</h3>
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-control" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach (['Pria','Wanita','Branded','Sale'] as $cat): ?>
                    <option value="<?= $cat ?>" <?= ($_POST['category'] ?? ($product['category'] ?? '')) === $cat ? 'selected' : '' ?>><?= $cat === 'Sale' ? 'Negotiable' : $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kondisi</label>
                <select name="condition_item" class="form-control" required>
                    <option value="">Pilih kondisi</option>
                    <?php foreach (['Sangat baik','Baik','Cukup','Kurang'] as $c): ?>
                    <option value="<?= $c ?>" <?= ($_POST['condition_item'] ?? ($product['condition_item'] ?? '')) === $c ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ukuran</label>
                    <select name="size" class="form-control">
                        <option value="">Pilih ukuran</option>
                        <?php foreach (['XS','S','M','L','XL','XXL','XXXL'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($_POST['size'] ?? ($product['size'] ?? '')) === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" placeholder="Brand"
                           value="<?= sanitize($_POST['brand'] ?? ($product['brand'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Warna</label>
                <input type="text" name="color" class="form-control" placeholder="cth: Black, White"
                       value="<?= sanitize($_POST['color'] ?? ($product['color'] ?? '')) ?>">
            </div>

            <!-- Measurements -->
            <div class="form-group">
                <label class="form-label">Measurements (opsional)</label>
                <div id="measurements">
                    <?php
                    $measLabels = $_POST['meas_label'] ?? [];
                    $measValues = $_POST['meas_value'] ?? [];
                    if ($measLabels):
                        foreach ($measLabels as $k => $label):
                            $value = $measValues[$k] ?? '';
                    ?>
                    <div class="meas-row">
                        <input type="text" name="meas_label[]" class="form-control" placeholder="Label (cth: Panjang)" value="<?= sanitize($label) ?>">
                        <input type="text" name="meas_value[]" class="form-control" placeholder="Nilai (cth: 70cm)" value="<?= sanitize($value) ?>">
                        <button type="button" onclick="removeMeas(this)" class="btn-ghost">✕</button>
                    </div>
                    <?php endforeach;
                    elseif (!empty($measurements)):
                        foreach ($measurements as $m): ?>
                    <div class="meas-row">
                        <input type="text" name="meas_label[]" class="form-control" placeholder="Label (cth: Panjang)" value="<?= sanitize($m['label']) ?>">
                        <input type="text" name="meas_value[]" class="form-control" placeholder="Nilai (cth: 70cm)" value="<?= sanitize($m['value']) ?>">
                        <button type="button" onclick="removeMeas(this)" class="btn-ghost">✕</button>
                    </div>
                    <?php endforeach;
                    else: ?>
                    <div class="meas-row">
                        <input type="text" name="meas_label[]" class="form-control" placeholder="Label (cth: Panjang)">
                        <input type="text" name="meas_value[]" class="form-control" placeholder="Nilai (cth: 70cm)">
                        <button type="button" onclick="removeMeas(this)" class="btn-ghost">✕</button>
                    </div>
                    <?php endif; ?>
                </div>
                <button type="button" onclick="addMeas()" class="btn-outline btn-sm">+ Tambah measurement</button>
            </div>
        </section>

        <!-- Price -->
        <section class="form-section">
            <label class="form-label">Harga (Rp)</label>
            <input type="number" name="price" class="form-control" placeholder="0"
                   value="<?= (int)($_POST['price'] ?? ($product['price'] ?? 0)) ?: '' ?>" min="1000" required>
            <label class="checkbox-label">
                <input type="checkbox" name="negotiable" value="1" <?= (!empty($_POST) ? isset($_POST['negotiable']) : ($product['is_negotiable'] ?? 0)) ? 'checked' : '' ?>>
                Bisa ditawar
            </label>
        </section>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan perubahan</button>
            <a href="<?= BASE_URL ?>index.php?page=product&id=<?= (int)($product['id'] ?? 0) ?>" class="btn-outline">Batal</a>
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
