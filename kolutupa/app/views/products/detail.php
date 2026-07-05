<?php // app/views/products/detail.php ?>
<div class="product-detail container">
    <div class="product-layout">
        <!-- Images -->
        <div class="product-gallery">
            <div class="main-image">
                <img id="mainImg"
                     src="<?= productImageUrl(!empty($images) ? $images[0]['image_path'] : null) ?>"
                     alt="<?= sanitize($product['title']) ?>">
            </div>
            <?php if (count($images) > 1): ?>
            <div class="thumbnails">
                <?php foreach ($images as $img): ?>
                <img src="<?= productImageUrl($img['image_path']) ?>"
                     alt="" class="thumb" onclick="switchImage(this)">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div class="product-info-panel">
            <h1 class="product-title"><?= sanitize($product['title']) ?></h1>
            <p class="product-meta"><?= sanitize($product['size'] ?? '') ?> &nbsp; <?= sanitize($product['condition_item']) ?></p>
            <p class="product-price-big"><?= formatRupiah($product['price']) ?></p>
            <?php if (($product['status'] ?? 'active') === 'sold'): ?>
                <div class="sold-banner" style="background:#fff4f4;border:1px solid #f3c2c2;color:#9b1c1c;padding:10px 12px;border-radius:10px;margin-bottom:12px;font-weight:600;">SOLD OUT — barang ini sudah terjual dan tidak bisa dibeli lagi.</div>
            <?php endif; ?>

            <?php if ($product['seller_id'] == ($_SESSION['user_id'] ?? 0) && ($product['status'] ?? 'active') !== 'sold'): ?>
                <a href="<?= BASE_URL ?>index.php?page=edit-product&id=<?= $product['id'] ?>" class="btn-primary btn-full">
                    Edit Produk
                </a>
            <?php endif; ?>

            <?php if ($product['seller_id'] != ($_SESSION['user_id'] ?? 0)): ?>
                <?php if (($product['status'] ?? 'active') === 'sold'): ?>
                    <button type="button" class="btn-primary btn-full" disabled style="opacity:.7;cursor:not-allowed">Barang Sudah Terjual</button>
                    <button type="button" class="btn-outline btn-full" disabled style="opacity:.7;cursor:not-allowed">+ Keranjang</button>
                <?php else: ?>
                    <form method="POST" action="<?= BASE_URL ?>index.php?action=add-to-cart" style="margin-bottom:8px">
                        <?= csrf() ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn-primary btn-full">Beli Langsung</button>
                    </form>
                    <form method="POST" action="<?= BASE_URL ?>index.php?action=add-to-cart" style="margin-bottom:8px">
                        <?= csrf() ?>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn-outline btn-full" id="cartBtn">+ Keranjang</button>
                    </form>
                <?php endif; ?>
                <?php if (($product['status'] ?? 'active') !== 'sold' && $product['is_negotiable']): ?>
                <a href="<?= BASE_URL ?>index.php?page=nego&product_id=<?= $product['id'] ?>" class="btn-outline btn-full">Nego</a>
                <?php endif; ?>

                <?php if (isLoggedIn()): ?>
                <button type="button" class="btn-outline btn-full like-btn <?= $isLiked ? 'liked' : '' ?>" 
                        data-product-id="<?= $product['id'] ?>"
                        style="border-color: <?= $isLiked ? '#e74c3c' : '' ?>; color: <?= $isLiked ? '#e74c3c' : '' ?>;">
                    <i class="fa-<?= $isLiked ? 'solid' : 'regular' ?> fa-heart"></i>
                    <?= $isLiked ? 'Hapus dari Favorit' : 'Simpan ke Favorit' ?>
                </button>
                <?php else: ?>
                <a href="<?= BASE_URL ?>index.php?page=login" class="btn-outline btn-full">
                    <i class="fa-regular fa-heart"></i>
                    Simpan ke Favorit
                </a>
                <?php endif; ?>
            <?php endif; ?>

<!-- Details -->
<div class="product-details">
    <h3>Detail</h3>

    <?php if (!empty($product['brand'])): ?>
        <p><strong>Brand:</strong> <?= sanitize($product['brand']) ?></p>
    <?php endif; ?>
    <?php if ($measurements): ?>
    <div class="measurements">
        <?php foreach ($measurements as $m): ?>
        <p><?= sanitize($m['label']) ?> - <?= sanitize($m['value']) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($product['description']): ?>
    <p class="desc-text"><?= nl2br(sanitize($product['description'])) ?></p>
    <?php endif; ?>

    <?php if ($product['color']): ?>
    <div class="color-tags">
        <?php foreach (explode(',', $product['color']) as $c): ?>
        <span class="tag"><?= sanitize(trim($c)) ?></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
            

            <!-- Buyer Protection -->
            <div class="buyer-protection">
                <span>🛡️</span>
                <div>
                    <strong>Perlindungan Pembeli</strong>
                    <p>Belanja di KOLUTUPA aman garansi pengembalian dana.</p>
                </div>
            </div>

            <!-- Seller Info -->
            <a href="<?= BASE_URL ?>index.php?page=seller&username=<?= $product['username'] ?>" class="seller-info-card">
                <img src="<?= avatarUrl($product['seller_avatar']) ?>" alt="<?= sanitize($product['seller_name']) ?>">
                <div>
                    <strong><?= sanitize($product['seller_name']) ?></strong>
                    <p><?= sanitize($product['seller_city'] ?? '') ?></p>
                    <div class="stars-sm"><?= stars($product['seller_rating']) ?></div>
                </div>
            </a>
        </div>
    </div>

    <!-- Reviews -->
    <?php if ($reviews): ?>
    <div class="reviews-section">
        <h2>Reviews</h2>
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
                        <img src="<?= avatarUrl($r['reviewer_avatar']) ?>" alt="">
                        <div>
                            <strong><?= sanitize($r['reviewer_name']) ?></strong>
                            <small>Pembeli</small>
                        </div>
                        <?php if ($r['product_image']): ?>
                        <img src="<?= productImageUrl($r['product_image']) ?>" class="review-product-img" alt="">
                        <?php endif; ?>
                    </div>
                    <div class="stars-sm"><?= stars($r['rating']) ?></div>
                    <p><?= sanitize($r['comment'] ?? '') ?></p>
                    <small><?= timeAgo($r['created_at']) ?></small>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Related Products -->
    <?php if ($relatedProducts): ?>
    <div class="related-section">
        <div class="section-header">
            <h2>Lainnya dari seller</h2>
            <a href="<?= BASE_URL ?>index.php?page=seller&username=<?= $product['username'] ?>" class="see-all">→</a>
        </div>
        <div class="product-grid">
            <?php foreach ($relatedProducts as $rp): ?>
            <?php $product_tmp = $rp; include BASE_PATH . '/app/views/partials/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
