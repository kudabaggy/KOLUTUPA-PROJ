<?php // app/views/profile/show.php ?>
<div class="profile-page container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            <img src="<?= avatarUrl($user['avatar']) ?>" alt="<?= sanitize($user['name']) ?>">
        </div>
        <div class="profile-meta">
            <h1><?= sanitize($user['name']) ?></h1>
            <div class="profile-stars"><?= stars($reviewScore['avg']) ?></div>
            <div class="profile-stats">
                <span><strong><?= number_format($followers) ?></strong> Followers</span>
                <span><strong><?= number_format($following) ?></strong> Following</span>
            </div>
            <?php if ($user['bio']): ?>
            <p class="profile-bio"><?= sanitize($user['bio']) ?></p>
            <?php endif; ?>

            <?php if ($isOwner): ?>
                <div class="profile-actions">
                    <a href="<?= BASE_URL ?>index.php?page=settings" class="btn-outline">Edit profil</a>
                    <a href="<?= BASE_URL ?>index.php?page=add-product" class="btn-primary">Tambah produk</a>
                </div>
            <?php else: ?>
                <div class="profile-actions">
                    <button class="btn-primary follow-btn <?= $isFollowing ? 'following' : '' ?>"
                            data-user-id="<?= $user['id'] ?>"
                            data-following="<?= $isFollowing ? '1' : '0' ?>">
                        <?= $isFollowing ? 'Following' : 'Follow' ?>
                    </button>
                    <a href="<?= BASE_URL ?>index.php?page=messages&user=<?= $user['id'] ?>" class="btn-outline btn-icon">✉️</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tabs -->
    <div class="profile-tabs">
        <button class="tab-btn active" data-tab="shop">Shop</button>
        <button class="tab-btn" data-tab="likes">Likes</button>
        <button class="tab-btn" data-tab="reviews">Reviews</button>
    </div>

    <!-- Shop Tab -->
    <div class="tab-content active" id="tab-shop">
        <?php if (empty($products)): ?>
            <div class="empty-state"><p>Belum ada produk.</p></div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <?php include BASE_PATH . '/app/views/partials/product_card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Likes Tab -->
    <div class="tab-content" id="tab-likes" style="display:none">
        <?php if (empty($liked)): ?>
            <div class="empty-state"><p>Belum ada produk yang disukai.</p></div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($liked as $product): ?>
                    <?php include BASE_PATH . '/app/views/partials/product_card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Reviews Tab -->
    <div class="tab-content" id="tab-reviews" style="display:none">
        <?php if (empty($reviews)): ?>
            <div class="empty-state"><p>Belum ada review.</p></div>
        <?php else: ?>
            <div class="reviews-layout">
                <div class="rating-summary">
                    <div class="avg-score"><?= number_format($reviewScore['avg'], 1) ?> ⭐</div>
                    <p><?= $reviewScore['total'] ?> Reviews</p>
                    <div class="breakdown">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <div class="bar-row">
                            <span><?= $i ?></span>
                            <div class="bar-track">
                                <?php $pct = $reviewScore['total'] ? round(($breakdown[$i] / $reviewScore['total']) * 100) : 0; ?>
                                <div class="bar-fill" style="width:<?= $pct ?>%"></div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="reviews-list">
                    <?php foreach ($reviews as $r): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <img src="<?= avatarUrl($reviewMode === 'written' ? $r['seller_avatar'] : $r['reviewer_avatar']) ?>" alt="">
                            <div>
                                <strong><?= sanitize($reviewMode === 'written' ? $r['seller_name'] : $r['reviewer_name']) ?></strong>
                                <small><?= $reviewMode === 'written' ? 'Penjual' : 'Pembeli' ?></small>
                            </div>
                            <?php if (!empty($r['product_image'])): ?>
                            <img src="<?= productImageUrl($r['product_image']) ?>" class="review-product-img" alt="">
                            <?php endif; ?>
                        </div>
                        <div class="stars-sm"><?= stars($r['rating']) ?></div>
                        <p><?= sanitize($r['comment'] ?? '') ?></p>
                        <?php if (!empty($r['product_title'])): ?>
                        <small class="review-product-title">Produk: <?= sanitize($r['product_title']) ?></small>
                        <?php endif; ?>
                        <small><?= timeAgo($r['created_at']) ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Profile tabs
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).style.display = 'block';
    });
});

// Follow button
const followBtn = document.querySelector('.follow-btn');
if (followBtn) {
    followBtn.addEventListener('click', async () => {
        const userId = followBtn.dataset.userId;
        const res = await fetch('<?= BASE_URL ?>index.php?action=toggle-follow', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'user_id=' + userId + '&csrf_token=<?= $_SESSION["csrf_token"] ?? "" ?>'
        });
        const data = await res.json();
        followBtn.textContent = data.following ? 'Following' : 'Follow';
        followBtn.classList.toggle('following', data.following);
    });
}
</script>
