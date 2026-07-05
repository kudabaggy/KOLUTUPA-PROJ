<?php // app/views/notifications/index.php ?>
<div class="container notifications-page">
    <div class="notif-box">
        <h2>Notifikasi</h2>
        <?php if (empty($notifications)): ?>
            <div class="empty-state"><p>Tidak ada notifikasi.</p></div>
        <?php else: ?>
            <?php
            $grouped = [];
            foreach ($notifications as $n) {
                $key = (new DateTime($n['created_at']))->format('Y-m-d');
                $grouped[$key][] = $n;
            }
            ?>
            <?php foreach ($grouped as $date => $items): ?>
            <p class="notif-date"><?= timeAgo($date . ' 00:00:00') ?></p>
            <?php foreach ($items as $n): ?>
            <?php
                $url = null;
                if (!empty($n['order_id'])) {
                    $url = BASE_URL . "index.php?page=order-detail&order_id=" . (int)$n['order_id'];
                } elseif (!empty($n['type']) && in_array($n['type'], ['order_shipped','order_review','order_processed','order_payment','order_canceled'])) {
                    $url = BASE_URL . "index.php?page=orders&tab=dibeli";
                }
                if (empty($url) && !empty($n['product_id'])) {
                    $url = BASE_URL . "index.php?page=product&id=" . (int)$n['product_id'];
                }
            ?>
            <?php if ($url): ?>
            <a href="<?= $url ?>" class="notif-item <?= $n['is_read'] ? '' : 'unread' ?> notif-link">
            <?php else: ?>
            <div class="notif-item <?= $n['is_read'] ? '' : 'unread' ?>">
            <?php endif; ?>
                <img src="<?= avatarUrl($n['from_avatar'] ?? null) ?>" alt="" class="notif-avatar">
                <div class="notif-text">
                    <p><strong><?= sanitize($n['from_name'] ?? 'System') ?></strong> <?= sanitize($n['message']) ?></p>
                    <small><?= timeAgo($n['created_at']) ?></small>
                </div>
                <?php if ($n['product_image']): ?>
                <img src="<?= productImageUrl($n['product_image']) ?>" alt="" class="notif-product-img">
                <?php endif; ?>
            <?php if ($url): ?>
            </a>
            <?php else: ?>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
