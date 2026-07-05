<?php // app/views/orders/review.php ?>
<div class="checkout-page">
    <div class="checkout-protection">
        <span>📝 Berikan Review</span>
        <p>Bagikan pengalaman belanja Anda untuk membantu pembeli lain dan memberi feedback kepada penjual.</p>
    </div>

    <div class="checkout-layout">
        <div class="checkout-form">
            <h2>Ulas Pesanan</h2>
            <div class="order-summary-box" style="margin-bottom:24px;">
                <h3>Pesanan selesai</h3>
                <p>Invoice #: <?= sanitize($order['invoice_number']) ?></p>
                <p><strong><?= sanitize($product['title']) ?></strong></p>
                <p>Penjual: <?= sanitize($product['seller_name']) ?></p>
                <p>Total: <?= formatRupiah($order['total_amount']) ?></p>
            </div>
            <form method="POST" action="<?= BASE_URL ?>index.php?action=submit-review">
                <?= csrf() ?>
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

                <label class="form-label">Rating</label>
                <div class="rating-options">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                    <label class="rating-option">
                        <input type="radio" name="rating" value="<?= $i ?>" required>
                        <span><?= str_repeat('★', $i) ?></span>
                    </label>
                    <?php endfor; ?>
                </div>

                <label class="form-label" style="display:block; margin-top:18px;">Komentar</label>
                <textarea name="comment" class="form-control" rows="5" placeholder="Tulis ulasan Anda tentang produk dan penjual"></textarea>

                <button type="submit" class="btn-primary btn-full" style="margin-top:18px">Kirim Review</button>
            </form>
        </div>

        <div class="checkout-summary">
            <h3>Produk</h3>
            <div class="checkout-item">
                <img src="<?= productImageUrl($product['primary_image'] ?? null) ?>" alt="">
                <div>
                    <p><?= sanitize($product['title']) ?></p>
                    <p><?= formatRupiah($product['price']) ?></p>
                    <p><?= sanitize($product['size'] ?? '') ?></p>
                </div>
            </div>
            <div class="checkout-seller">
                <img src="<?= avatarUrl($product['seller_avatar']) ?>" alt="">
                <div>
                    <strong><?= sanitize($product['seller_name']) ?></strong>
                    <p><?= sanitize($product['seller_city'] ?? '') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
