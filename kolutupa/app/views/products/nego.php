<?php // app/views/products/nego.php ?>
<div class="nego-page">
    <h1>Nego</h1>
    <hr>
    <div class="nego-product">
        <img src="<?= productImageUrl($product['primary_image'] ?? null) ?>" alt="<?= sanitize($product['title']) ?>">
        <div>
            <h2><?= sanitize($product['title']) ?></h2>
            <p class="nego-orig-price"><?= formatRupiah($product['price']) ?></p>
        </div>
    </div>
    <hr>
    <form method="POST" action="<?= BASE_URL ?>index.php?action=submit-nego">
        <?= csrf() ?>
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <div class="nego-price-section">
            <h3>Harga nego</h3>
            <div class="nego-input-wrap">
                <span>Rp</span>
                <input type="number" name="offered_price" placeholder="0" required min="1000"
                       max="<?= $product['price'] ?>" class="nego-input">
            </div>
            <small>*Belum termasuk ongkir</small>
        </div>
        <button type="submit" class="btn-primary btn-full btn-large">Kirim Nego</button>
    </form>
</div>
