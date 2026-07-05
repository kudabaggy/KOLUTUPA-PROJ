<?php // app/views/products/favorites.php ?>
<div class="container" style="padding: 40px 0;">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; margin-bottom: 10px;">❤️ Produk Favorit Saya</h1>
        <p style="color: #666; font-size: 14px;">Koleksi produk yang Anda sukai</p>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state" style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; margin-bottom: 20px;">💔</div>
            <p style="font-size: 16px; color: #666; margin-bottom: 10px;">Belum ada produk favorit</p>
            <p style="color: #999; margin-bottom: 30px;">Mulai jelajahi dan tambahkan produk ke favorit Anda</p>
            <a href="<?= BASE_URL ?>index.php?page=home" class="btn-primary" style="display: inline-block;">
                Lihat Koleksi
            </a>
        </div>
    <?php else: ?>
        <div style="margin-bottom: 20px; color: #666;">
            Total: <strong><?= count($products) ?></strong> produk favorit
        </div>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php include BASE_PATH . '/app/views/partials/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.empty-state {
    border: 2px dashed #ddd;
    border-radius: 12px;
    background: #f9f9f9;
}
</style>
