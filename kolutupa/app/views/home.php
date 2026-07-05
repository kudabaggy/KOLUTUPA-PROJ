<?php // app/views/home.php ?>

<!-- Hero Banner -->
<div class="container">
    <section class="hero-banner" style="background-image: url('<?= BASE_URL ?>assets/images/bg1.jpeg');">
        <div class="hero-content">
            <h1>Jual-Beli<br>baju preloved<br>dan thrift</h1>

            <div class="hero-btns">
                <a href="<?= BASE_URL ?>index.php?page=add-product" class="btn-primary">
                    Mulai berjualan
                </a>
            
                <a href="#how-it-works" class="btn-outline">Cara kerjanya</a>
            </div>
        </div>
    </section>
</div>

<!-- How It Works -->
<section class="how-it-works container" id="how-it-works">
    <h2 >HOW TO USE KOLUTUPA</h2>
    <div class="how-steps">
        <a href="<?= BASE_URL ?>index.php?page=cara-jualan" class="step">
            <div class="step-icon">
                <i class="fa-solid fa-store"></i>
            </div>
            <p>Cara Jualan</p>
        </a>

        <a href="<?= BASE_URL ?>index.php?page=cara-belanja" class="step">
            <div class="step-icon">
             <i class="fa-solid fa-cart-shopping"></i>
         </div>
            <p>Cara Belanja</p>
        </a>

        <a href="<?= BASE_URL ?>index.php?page=keamanan" class="step">
         <div class="step-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <p>Keamanan</p>
        </a>
    </div>
</section>

<!-- Hot Items -->
<section class="container section">
    <div class="section-header">
        <h2>Hot Items</h2>
        <a href="<?= BASE_URL ?>index.php?page=category&cat=all" class="see-all">→</a>
    </div>
    <div class="product-grid">
        <?php foreach ($hotItems as $product): ?>
            <?php include BASE_PATH . '/app/views/partials/product_card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- Recommended Sellers -->
<section class="container section">
    <h2>Rekomendasi Seller</h2>
    <div class="sellers-grid">
        <?php foreach ($sellers as $seller): ?>
        <a href="<?= BASE_URL ?>index.php?page=seller&username=<?= urlencode($seller['username']) ?>" class="seller-card">
            <div class="seller-media">
                <?php if (!empty($seller['sample_image'])): ?>
                    <img src="<?= productImageUrl($seller['sample_image']) ?>" alt="<?= sanitize($seller['name']) ?>">
                <?php else: ?>
                    <img src="<?= avatarUrl($seller['avatar']) ?>" alt="<?= sanitize($seller['name']) ?>">
                <?php endif; ?>
            </div>

            <div class="seller-info">
                <strong class="seller-name"><?= sanitize($seller['name'] ?: $seller['username']) ?></strong>
                <div class="seller-rating">
                    <?= stars((float)($seller['avg_rating'] ?? 0)) ?>
                    <span class="rating-num"><?= number_format($seller['avg_rating'] ?? 0, 1) ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Buyer Protection -->
<section class="protection-section">
    <div class="container">
        <h2>Aman dan terlindungi</h2>
        <p class="section-sub">Komunitas jual beli aman dan seru, tempat pembeli dan penjual berbagi review asli yang terverifikasi.</p>
        <div class="reviews-grid">
            <?php foreach ($testimonials as $t): ?>
            <div class="review-card">
                <div class="stars"><?= stars(5) ?></div>
                <p><?= sanitize($t['comment']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
