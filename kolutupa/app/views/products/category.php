<?php // app/views/products/category.php ?>
<div class="container category-page">
    <h1><?= sanitize($label) ?></h1>

    <form class="filter-bar" method="GET" action="">
        <input type="hidden" name="page" value="category">
        <input type="hidden" name="cat" value="<?= sanitize($category) ?>">
        <select name="size" class="filter-select">
            <option value="">Size</option>
            <?php foreach (['XS','S','M','L','XL','XXL'] as $s): ?>
            <option value="<?= $s ?>" <?= ($filters['size'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
        <select name="condition" class="filter-select">
            <option value="">Kondisi</option>
            <?php foreach (['Sangat baik','Baik','Cukup'] as $c): ?>
            <option value="<?= $c ?>" <?= ($filters['condition'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="min_price" placeholder="Harga min" value="<?= $filters['min_price'] ?? '' ?>" class="filter-input">
        <input type="number" name="max_price" placeholder="Harga max" value="<?= $filters['max_price'] ?? '' ?>" class="filter-input">
        <input type="text" name="brand" placeholder="Brand" value="<?= sanitize($filters['brand'] ?? '') ?>" class="filter-input">
        <button type="submit" class="btn-primary">Filter</button>
        <a href="<?= BASE_URL ?>index.php?page=category&cat=<?= urlencode($category) ?>" class="btn-ghost">Reset</a>
    </form>

    <?php if (empty($products)): ?>
        <div class="empty-state"><p>Tidak ada produk ditemukan.</p></div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php include BASE_PATH . '/app/views/partials/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
