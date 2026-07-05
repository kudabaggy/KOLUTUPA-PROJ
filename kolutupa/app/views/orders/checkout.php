<?php // app/views/orders/checkout.php ?>
<div class="checkout-page">
    <div class="checkout-protection">
        <span>🛡️ Perlindungan Pembeli</span>
        <p>Belanja di KOLUTUPA aman garansi pengembalian dana.</p>
    </div>
    <div class="checkout-layout">
        <div class="checkout-form">
            <h2>Alamat</h2>
            <form method="POST" action="<?= BASE_URL ?>index.php?action=place-order">
                <?= csrf() ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="text" name="recipient_name" class="form-control" placeholder="Nama penerima" required>
                <input type="text" name="recipient_phone" class="form-control" placeholder="Nomor telepon" required>
                <textarea name="shipping_address" class="form-control" rows="3" placeholder="Alamat lengkap" required></textarea>
                <small>Pastikan alamat benar</small>
                <input type="text" name="shipping_detail" class="form-control" placeholder="Detail lainnya (opsional)">
                <small>cth: blok, unit, nomor rumah</small>
                <?php if (!empty($negotiation)): ?>
                    <input type="hidden" name="nego_id" value="<?= $negotiation['id'] ?>">
                <?php endif; ?>
                <button type="submit" class="btn-primary btn-full" style="margin-top:16px">Pilih pengiriman</button>
            </form>
        </div>
        <div class="checkout-summary">
            <h3>Seller</h3>
            <div class="checkout-seller">
                <img src="<?= avatarUrl($product['seller_avatar']) ?>" alt="">
                <div>
                    <strong><?= sanitize($product['seller_name']) ?></strong>
                    <p><?= sanitize($product['seller_city'] ?? '') ?></p>
                    <div class="stars-sm"><?= stars($product['seller_rating']) ?> (<?= $product['review_count'] ?? 0 ?>)</div>
                </div>
            </div>
            <h3>Order</h3>
            <div class="checkout-item">
                <img src="<?= productImageUrl($product['primary_image'] ?? null) ?>" alt="">
                <div>
                    <p><?= sanitize($product['title']) ?></p>
                    <p>
                        <?php if (!empty($product['negotiated_price'])): ?>
                            <?= formatRupiah($product['negotiated_price']) ?> <span class="price-original">(Harga asli <?= formatRupiah($product['price']) ?>)</span>
                        <?php else: ?>
                            <?= formatRupiah($product['price']) ?>
                        <?php endif; ?>
                    </p>
                    <p><?= sanitize($product['size'] ?? '') ?></p>
                </div>
            </div>
            <div class="checkout-totals">
                <div class="total-row"><span>1 Item</span><span><?= formatRupiah($product['negotiated_price'] ?? $product['price']) ?></span></div>
                <div class="total-row total-final"><strong>Total</strong><strong><?= formatRupiah(($product['negotiated_price'] ?? $product['price']) + 20000) ?></strong></div>
            </div>
        </div>
    </div>
</div>
