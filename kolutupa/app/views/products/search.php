<?php // app/views/products/search.php ?>
<div class="container">
    <h2>Hasil pencarian: "<?= sanitize($q) ?>"</h2>
    <?php if (empty($products)): ?>
        <div class="empty-state"><p>Tidak ada produk ditemukan untuk "<?= sanitize($q) ?>".</p></div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php include BASE_PATH . '/app/views/partials/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
