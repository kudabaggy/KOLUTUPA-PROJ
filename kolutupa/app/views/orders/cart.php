<?php // app/views/orders/cart.php ?>
<div class="container">
    <h1>Keranjang</h1>
    <?php if (empty($items)): ?>
        <div class="empty-state"><div class="empty-icon">🛒</div><p>Keranjang kamu kosong.</p></div>
    <?php else: ?>
        <?php
        // Group by seller
        $grouped = [];
        foreach ($items as $item) $grouped[$item['seller_username']][] = $item;
        ?>
        <?php foreach ($grouped as $sellerUsername => $sellerItems): ?>
        <div class="cart-group">
            <div class="cart-seller">
                <img src="<?= avatarUrl($sellerItems[0]['seller_avatar'] ?? null) ?>" alt="">
                <strong><?= sanitize($sellerItems[0]['seller_name']) ?></strong>
            </div>
            <?php foreach ($sellerItems as $item): ?>
            <div class="cart-item">
                <img src="<?= productImageUrl($item['product_image']) ?>" alt="<?= sanitize($item['title']) ?>">
                <div class="cart-item-info">
                    <p class="cart-item-title"><?= sanitize($item['title']) ?></p>
                    <p><?= formatRupiah($item['price']) ?></p>
                    <p><?= sanitize($item['size'] ?? '') ?></p>
                </div>
                <div class="cart-item-actions">
                    <strong><?= formatRupiah($item['price']) ?></strong>
                    <p class="cart-note">Ongkir di hitung saat check out</p>
                    <a href="<?= BASE_URL ?>index.php?page=checkout&product_id=<?= $item['product_id'] ?>" class="btn-primary">
                        Check out 1 item
                    </a>
                    <form method="POST" action="<?= BASE_URL ?>index.php?action=remove-from-cart" style="margin-top:8px">
                        <?= csrf() ?>
                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                        <button type="submit" class="btn-ghost btn-sm">Hapus</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
