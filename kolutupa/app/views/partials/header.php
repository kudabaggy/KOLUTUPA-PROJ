<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <meta name="description" content="KOLUTUPA - Jual beli baju preloved dan thrift terpercaya">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<?php
// Compute badge counts
$cartCount  = isLoggedIn() ? (new CartModel())->countCart($_SESSION['user_id']) : 0;
$notifCount = isLoggedIn() ? (new NotificationModel())->countUnread($_SESSION['user_id']) : 0;
$msgCount   = isLoggedIn() ? (new MessageModel())->countUnread($_SESSION['user_id']) : 0;
$currentPage = sanitize($_GET['page'] ?? 'home');
$flashMsg = flash();
?>

<header class="site-header">
    <div class="header-inner">
        <a href="<?= BASE_URL ?>index.php"
           class="logo"
           style="background-image: url('<?= BASE_URL ?>assets/images/lg.jpeg');">
        </a>

        <form class="search-form" action="<?= BASE_URL ?>index.php" method="GET">
            <input type="hidden" name="page" value="search">

            <input
                type="search"
                name="q"
                placeholder="Cari produk, brand, seller..."
                value="<?= sanitize($_GET['q'] ?? '') ?>">

            <button type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <nav class="header-actions">
            <?php if (isLoggedIn()): ?>

                <a href="<?= BASE_URL ?>index.php?page=add-product"
                   class="btn-sell">
                    Jual
                </a>

                <!-- Pesan -->
                <a href="<?= BASE_URL ?>index.php?page=messages"
                   class="icon-btn <?= $msgCount > 0 ? 'has-badge' : '' ?>"
                   title="Pesan">

                    <i class="fa-regular fa-envelope"></i>

                    <?php if ($msgCount > 0): ?>
                        <span class="badge"><?= $msgCount ?></span>
                    <?php endif; ?>
                </a>

                <!-- Notifikasi -->
                <a href="<?= BASE_URL ?>index.php?page=notifications"
                   class="icon-btn <?= $notifCount > 0 ? 'has-badge' : '' ?>"
                   title="Notifikasi">

                    <i class="fa-regular fa-bell"></i>

                    <?php if ($notifCount > 0): ?>
                        <span class="badge"><?= $notifCount ?></span>
                    <?php endif; ?>
                </a>

                <!-- Keranjang -->
                <a href="<?= BASE_URL ?>index.php?page=cart"
                   class="icon-btn <?= $cartCount > 0 ? 'has-badge' : '' ?>"
                   title="Keranjang">

                    <i class="fa-solid fa-cart-shopping"></i>

                    <?php if ($cartCount > 0): ?>
                        <span class="badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>

                <div class="user-menu">
                    <button class="avatar-btn" id="avatarBtn">
                        <i class="fa-regular fa-user"></i>
                    </button>       

                    <div class="dropdown-menu" id="dropdownMenu">

                        <div class="dropdown-user">
                             <i class="fa-regular fa-user"></i>

                            <div>
                                <strong><?= sanitize($_SESSION['user_name'] ?? '') ?></strong>
                                <small>Lihat Profil</small>
                            </div>
                        </div>
                        <a href="<?= BASE_URL ?>index.php?page=profile">
                            <i class="fa-regular fa-user"></i>
                            Profil Saya
                        </a>

                        <a href="<?= BASE_URL ?>index.php?page=orders">
                            <i class="fa-solid fa-box"></i>
                            Pesanan
                        </a>

                        <a href="<?= BASE_URL ?>index.php?page=favorites">
                            <i class="fa-solid fa-heart"></i>
                            Produk Favorit
                        </a>

                        <a href="<?= BASE_URL ?>index.php?page=wallet"
                           class="d-flex justify-between">

                            <span>
                                <i class="fa-solid fa-wallet"></i>
                                Wallet
                            </span>

                            <span><?= formatRupiah(($currentUser['wallet_balance'] ?? 0)) ?></span>
                        </a>

                        <a href="<?= BASE_URL ?>index.php?page=settings">
                            <i class="fa-solid fa-gear"></i>
                            Settings
                        </a>

                        <a href="<?= BASE_URL ?>index.php?action=logout"
                           class="logout-link">

                            <i class="fa-solid fa-right-from-bracket"></i>
                            Log Out
                        </a>

                    </div>
                </div>

            <?php else: ?>

                <a href="<?= BASE_URL ?>index.php?page=login"
                   class="btn-outline">
                    Login
                </a>

                <a href="<?= BASE_URL ?>index.php?page=register"
                   class="btn-primary">
                    Daftar
                </a>

            <?php endif; ?>
        </nav>
    </div>

    <nav class="category-nav">
        <a href="<?= BASE_URL ?>index.php?page=home"
           class="home-link <?= ($currentPage === 'home') ? 'active' : '' ?>">
            Home
        </a>

        <a href="<?= BASE_URL ?>index.php?page=category&cat=Pria"
           class="<?= ($currentPage === 'category' && ($_GET['cat'] ?? '') === 'Pria') ? 'active' : '' ?>">
            Pria
        </a>

        <a href="<?= BASE_URL ?>index.php?page=category&cat=Wanita"
           class="<?= ($currentPage === 'category' && ($_GET['cat'] ?? '') === 'Wanita') ? 'active' : '' ?>">
            Wanita
        </a>

        <a href="<?= BASE_URL ?>index.php?page=category&cat=Branded"
           class="<?= ($currentPage === 'category' && ($_GET['cat'] ?? '') === 'Branded') ? 'active' : '' ?>">
            Branded
        </a>

        <a href="<?= BASE_URL ?>index.php?page=category&cat=Negotiable"
           class="sale-link <?= ($currentPage === 'category' && in_array($_GET['cat'] ?? '', ['Sale','Negotiable'])) ? 'active' : '' ?>">
            Negotiable
        </a>
    </nav>
</header>