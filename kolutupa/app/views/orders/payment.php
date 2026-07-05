<?php // app/views/orders/payment.php ?>
<div class="payment-page">
    <div class="checkout-protection">
        <span>🛡️ Perlindungan Pembeli</span>
        <p>Belanja di KOLUTUPA aman garansi pengembalian dana.</p>
    </div>
    <div class="checkout-layout">
        <div class="payment-left">
            <div class="payment-amount">IDR <?= number_format($order['total_amount'], 0, ',', '.') ?></div>
            <h3>Metode Pembayaran</h3>
            <form method="POST" action="<?= BASE_URL ?>index.php?action=confirm-payment">
                <?= csrf() ?>
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <p class="payment-note">Setelah pembayaran dikonfirmasi, penjual akan menyetujui pesanan sebelum diproses.</p>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="e_wallet" required>
                    <span>🪙 E-Wallet</span>
                    <span>›</span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="qr">
                    <span>📱 QR</span>
                    <span>›</span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="transfer_bank">
                    <span>🏦 Transfer Bank</span>
                    <span>›</span>
                </label>
                <button type="submit" class="btn-primary btn-full" style="margin-top:24px">Konfirmasi Pembayaran</button>
            </form>
        </div>
        <div class="payment-right">
            <div class="order-summary-box">
                <h3>Ringkasan Pesanan</h3>
                <p class="invoice-no">Invoice #: <?= sanitize($order['invoice_number']) ?></p>
                <?php if ($order['payment_deadline']): ?>
                <p class="payment-deadline">⏰ Bayar sebelum <?= date('d F Y H:i', strtotime($order['payment_deadline'])) ?></p>
                <?php endif; ?>
                <h4>Order</h4>
                <div class="checkout-item">
                    <img src="<?= productImageUrl($product['primary_image'] ?? null) ?>" alt="">
                    <div>
                        <p><?= sanitize($product['title']) ?></p>
                        <p><?= formatRupiah($order['product_price']) ?></p>
                        <p><?= sanitize($product['size'] ?? '') ?></p>
                    </div>
                </div>
                <div class="checkout-totals">
                    <div class="total-row"><span>1 Item</span><span><?= formatRupiah($order['product_price']) ?></span></div>
                    <div class="total-row"><span>Sub</span><span><?= formatRupiah($order['product_price']) ?></span></div>
                    <div class="total-row"><span>Pengiriman</span><span><?= formatRupiah($order['shipping_cost']) ?></span></div>
                    <div class="total-row total-final"><strong>Total yang harus dibayar</strong><strong><?= formatRupiah($order['total_amount']) ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
