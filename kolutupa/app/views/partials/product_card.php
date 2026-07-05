<?php
// app/views/partials/product_card.php
// Usage: include with $product in scope
?>
<a href="<?= BASE_URL ?>index.php?page=product&id=<?= $product['id'] ?>" class="product-card">
    <div class="product-img">
        <img src="<?= productImageUrl($product['primary_image'] ?? null) ?>"
             alt="<?= sanitize($product['title']) ?>"
             loading="lazy">
        <?php if (isset($product['status']) && $product['status'] === 'sold'): ?>
            <div class="sold-badge">TERJUAL</div>
        <?php endif; ?>
    </div>
    <div class="product-info">
        <h1 class="product-title" style="font-size:15px;"><?= sanitize($product['title']) ?></h1>
        <p class="product-price"><?= formatRupiah($product['price']) ?></p>
        <p class="product-size"><?= sanitize($product['size'] ?? 'M') ?></p>
        <?php if (!empty($product['brand'])): ?>
             <p class="product-brand">
        <strong>Brand:</strong> <?= sanitize($product['brand']) ?>
        </p>
            <?php endif; ?>
    </div>
</a>
