<?php // app/views/orders/detail.php ?>
<div class="container orders-page">
    <div class="order-card" style="padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h2 style="margin:0 0 6px;">Detail Pesanan</h2>
                <p style="margin:0; color:#666;">Invoice: <?= sanitize($order['invoice_number']) ?></p>
            </div>
            <span class="order-status status-<?= $order['status'] ?>"><?= ucfirst(str_replace('_', ' ', $order['status'])) ?></span>
        </div>

        <div class="order-item" style="display:flex; gap:16px; align-items:flex-start; flex-wrap:wrap;">
            <img src="<?= productImageUrl($product['primary_image'] ?? null) ?>" alt="" style="width:140px; border-radius:12px;">
            <div style="flex:1; min-width:260px;">
                <p style="margin:0 0 6px; font-weight:700; font-size:16px;"><?= sanitize($product['title'] ?? '') ?></p>
                <p style="margin:0 0 6px; color:#555;">Ukuran: <?= sanitize($product['size'] ?? '-') ?></p>
                <p style="margin:0 0 6px; color:#555;">Harga: <?= formatRupiah($order['product_price']) ?></p>
                <p style="margin:0 0 6px; color:#555;">Total: <?= formatRupiah($order['total_amount']) ?></p>
                <p style="margin:0 0 6px; color:#555;">Pembeli: <?= sanitize($buyer['name'] ?? '-') ?></p>
                <p style="margin:0; color:#555;">Penjual: <?= sanitize($seller['name'] ?? '-') ?></p>
            </div>
        </div>

        <div style="margin-top:18px; border-top:1px solid #eee; padding-top:16px; display:grid; gap:8px;">
            <p style="margin:0;"><strong>Alamat Pengiriman:</strong> <?= sanitize($order['shipping_address'] ?? '-') ?></p>
            <p style="margin:0;"><strong>Penerima:</strong> <?= sanitize($order['recipient_name'] ?? '-') ?></p>
            <p style="margin:0;"><strong>No. HP:</strong> <?= sanitize($order['recipient_phone'] ?? '-') ?></p>
            <p style="margin:0;"><strong>Metode Pembayaran:</strong> <?= sanitize($order['payment_method'] ?? '-') ?></p>
        </div>

        <div style="margin-top:18px; display:flex; gap:10px; flex-wrap:wrap;">
            <a href="<?= BASE_URL ?>index.php?page=orders&tab=<?= $isSeller ? 'terjual' : 'dibeli' ?>" class="btn-outline">Kembali</a>
            <?php if ($order['status'] === 'pending' && $isBuyer && empty($order['payment_method'])): ?>
                <a href="<?= BASE_URL ?>index.php?page=payment&order_id=<?= $order['id'] ?>" class="btn-primary">Bayar</a>
            <?php elseif ($order['status'] === 'dalam_proses' && $isBuyer): ?>
                <form method="POST" action="<?= BASE_URL ?>index.php?action=confirm-receipt" class="inline-form">
                    <?= csrf() ?>
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <button type="submit" class="btn-primary">Konfirmasi Terima</button>
                </form>
            <?php elseif ($order['status'] === 'selesai' && $isBuyer && empty($order['review_count'] ?? 0)): ?>
                <a href="<?= BASE_URL ?>index.php?page=review&order_id=<?= $order['id'] ?>" class="btn-primary">Tulis Review</a>
            <?php elseif ($order['status'] === 'pending' && $isSeller && !empty($order['payment_method'])): ?>
                <form method="POST" action="<?= BASE_URL ?>index.php?action=approve-order" class="inline-form">
                    <?= csrf() ?>
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <button type="submit" class="btn-primary">Setujui Pembelian</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
