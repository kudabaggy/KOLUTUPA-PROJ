<?php // app/views/orders/list.php ?>
<div class="orders-page container">
    <div class="orders-layout">
        <aside class="orders-sidebar">
            <a href="?page=orders&tab=terjual" class="orders-nav <?= $tab === 'terjual' ? 'active' : '' ?>">🛒 Terjual</a>
            <a href="?page=orders&tab=dibeli" class="orders-nav <?= $tab === 'dibeli' ? 'active' : '' ?>">🛍️ Dibeli</a>
        </aside>
        <div class="orders-content">
            <div class="orders-filter">
                <?php foreach ([''=>'Semua','dalam_proses'=>'Dalam Proses','dibatalkan'=>'Dibatalkan','selesai'=>'Selesai'] as $val => $label): ?>
                <a href="?page=orders&tab=<?= $tab ?>&status=<?= $val ?>"
                   class="filter-chip <?= $status === $val ? 'active' : '' ?>"><?= $label ?></a>
                <?php endforeach; ?>
            </div>
            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <div class="empty-icon">🛒</div>
                    <p><?= $tab === 'terjual' ? 'Belum ada penjualan' : 'Belum ada pembelian' ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $o): ?>
                <div class="order-card">
                    <div class="order-seller">
                        <img src="<?= avatarUrl($o['seller_avatar']) ?>" alt="">
                        <span><?= sanitize($tab === 'terjual' ? $o['buyer_name'] : $o['seller_name']) ?></span>
                        <span class="order-status status-<?= $o['status'] ?>"><?= ucfirst(str_replace('_', ' ', $o['status'])) ?></span>
                    </div>
                    <a href="<?= BASE_URL ?>index.php?page=order-detail&order_id=<?= $o['id'] ?>" class="order-item" style="display:flex; align-items:center; gap:16px; text-decoration:none; color:inherit;">
                        <img src="<?= productImageUrl($o['product_image']) ?>" alt="">
                        <div>
                            <p><?= sanitize($o['product_title']) ?></p>
                            <p><?= sanitize($o['product_size']) ?></p>
                            <p><?= formatRupiah($o['product_price']) ?></p>
                        </div>
                        <div class="order-total">
                            <strong><?= formatRupiah($o['total_amount']) ?></strong>
                            <?php if ($o['status'] === 'pending' && $tab === 'dibeli' && empty($o['payment_method'])): ?>
                            <a href="<?= BASE_URL ?>index.php?page=payment&order_id=<?= $o['id'] ?>" class="btn-primary btn-sm">Bayar</a>
                            <?php elseif ($o['status'] === 'pending' && $tab === 'dibeli' && !empty($o['payment_method'])): ?>
                            <span class="status-note">Menunggu persetujuan penjual</span>
                            <?php elseif ($o['status'] === 'dalam_proses' && $tab === 'dibeli'): ?>
                            <form method="POST" action="<?= BASE_URL ?>index.php?action=confirm-receipt" class="inline-form">
                                <?= csrf() ?>
                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                <button type="submit" class="btn-primary btn-sm">Konfirmasi Terima</button>
                            </form>
                            <?php elseif ($o['status'] === 'selesai' && $tab === 'dibeli' && empty($o['review_count'])): ?>
                            <a href="<?= BASE_URL ?>index.php?page=review&order_id=<?= $o['id'] ?>" class="btn-primary btn-sm">Tulis Review</a>
                            <?php elseif ($o['status'] === 'selesai' && $tab === 'dibeli' && !empty($o['review_count'])): ?>
                            <span class="status-note">Ulasan sudah dikirim</span>
                            <?php elseif ($o['status'] === 'pending' && $tab === 'terjual' && !empty($o['payment_method'])): ?>
                            <form method="POST" action="<?= BASE_URL ?>index.php?action=approve-order" class="inline-form">
                                <?= csrf() ?>
                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                <button type="submit" class="btn-primary btn-sm">Setujui Pembelian</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
