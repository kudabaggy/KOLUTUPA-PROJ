<?php
// public/index.php — Front Controller

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/Database.php';
require_once BASE_PATH . '/app/helpers.php';

// Auto-load models and controllers
$autoloadDirs = [
    BASE_PATH . '/app/models/',
    BASE_PATH . '/app/controllers/',
];
foreach ($autoloadDirs as $dir) {
    foreach (glob($dir . '*.php') as $file) {
        require_once $file;
    }
}

$page   = sanitize($_GET['page'] ?? 'home');
$action = sanitize($_GET['action'] ?? '');

// ─── ACTIONS (POST) ───────────────────────────────────────────────────────────
if ($action) {
    switch ($action) {
        case 'login':
            (new AuthController())->login(); break;
        case 'register':
            (new AuthController())->register(); break;
        case 'logout':
            (new AuthController())->logout(); break;
        case 'add-product':
            (new ProductController())->addProduct(); break;
        case 'update-product':
            (new ProductController())->updateProduct(); break;
        case 'toggle-like':
            (new ProductController())->toggleLike(); break;
        case 'update-profile':
            (new ProfileController())->updateProfile(); break;
        case 'update-account':
            (new ProfileController())->updateAccount(); break;
        case 'update-address':
            (new ProfileController())->updateAddress(); break;
        case 'toggle-follow':
            (new ProfileController())->toggleFollow(); break;
        case 'add-to-cart':
            (new CartController())->addToCart(); break;
        case 'remove-from-cart':
            (new CartController())->removeFromCart(); break;
        case 'place-order':
            (new OrderController())->placeOrder(); break;
        case 'confirm-payment':
            // Mark order as paid, seller still approves the order
            $orderId = (int)($_POST['order_id'] ?? 0);
            $method  = sanitize($_POST['payment_method'] ?? 'transfer_bank');
            if ($orderId && isLoggedIn()) {
                $orderModel = new OrderModel();
                $orderModel->update($orderId, ['payment_method' => $method]);
                // Remove from cart if exists
                $cartModel = new CartModel();
                $order = $orderModel->findById($orderId);
                if ($order) $cartModel->removeFromCart($_SESSION['user_id'], $order['product_id']);
                $_SESSION['flash'] = 'Pembayaran berhasil dikonfirmasi! Menunggu persetujuan penjual.';
            }
            redirect('index.php?page=orders&tab=dibeli');
            break;
        case 'approve-order':
            (new OrderController())->approveOrder(); break;
        case 'confirm-receipt':
            (new OrderController())->confirmReceipt(); break;
        case 'submit-review':
            (new OrderController())->submitReview(); break;
        case 'send-message':
            (new MessageController())->sendMessage(); break;
        case 'submit-nego':
            (new NegotiationController())->submitNego(); break;
        case 'accept-nego':
            (new NegotiationController())->acceptNego(); break;
        case 'reject-nego':
            (new NegotiationController())->rejectNego(); break;
        default:
            redirect('index.php');
    }
    exit;
}

// ─── PAGES (GET) ─────────────────────────────────────────────────────────────
switch ($page) {
    case 'home':
    case '':
        $productModel = new ProductModel();
        $reviewModel  = new ReviewModel();
        $hotItems     = $productModel->getHotItems(10);
        $sellers      = $productModel->getRecommendedSellers(4);
        $testimonials = $reviewModel->getForSeller(2, 8);
        if (empty($testimonials)) {
            $testimonials = [
                ['comment' => 'responsible seller and fast response, thank u so much 👍🏻'],
                ['comment' => 'Sesuai ekspektasi dan deskripsi…rekomended 👍🏻'],
                ['comment' => 'seller ramah, smooth transaction very puas'],
                ['comment' => 'makasih kak, produk conditionnya masih bagus dan sesuai'],
                ['comment' => 'barang nya masih bagus banget makasih kak'],
                ['comment' => 'sudah diterima dan barangnya tidak mengecewakan saya suka banget'],
                ['comment' => 'baguss bgt!! thanks min atas produknya, ori & gak megecewakan💗'],
                ['comment' => 'kendala dipengiriman saja, selebihnya aman'],
            ];
        }
        render('home', [
            'title'        => APP_NAME . ' - Jual Beli Baju Preloved & Thrift',
            'hotItems'     => $hotItems,
            'sellers'      => $sellers,
            'testimonials' => $testimonials,
        ]);
        break;

    case 'login':
        (new AuthController())->showLogin(); break;

    case 'register':
        (new AuthController())->showRegister(); break;

    case 'profile':
        (new ProfileController())->showProfile(); break;

    case 'seller':
        $username = sanitize($_GET['username'] ?? '');
        if (!empty($username)) {
            (new ProfileController())->showProfile($username);
        } else {
            redirect('index.php?page=home');
        }
        break;

    case 'settings':
        (new ProfileController())->showSettings(); break;

    case 'product':
        $id = (int)($_GET['id'] ?? 0);
        (new ProductController())->showProduct($id); break;

    case 'add-product':
        (new ProductController())->showAddProduct(); break;

    case 'edit-product':
        $id = (int)($_GET['id'] ?? 0);
        (new ProductController())->showEditProduct($id); break;

    case 'category':
        $cat = sanitize($_GET['cat'] ?? 'Pria');
        (new ProductController())->showCategory($cat); break;

    case 'search':
        (new ProductController())->search(); break;

    case 'cart':
        (new CartController())->showCart(); break;

    case 'checkout':
        $productId = (int)($_GET['product_id'] ?? 0);
        (new OrderController())->checkout($productId); break;

    case 'payment':
        $orderId = (int)($_GET['order_id'] ?? 0);
        (new OrderController())->showPayment($orderId); break;

    case 'orders':
        (new OrderController())->showOrders(); break;

    case 'favorites':
        (new ProductController())->showFavorites(); break;

    case 'order-detail':
        $orderId = (int)($_GET['order_id'] ?? 0);
        (new OrderController())->showOrderDetail($orderId); break;

    case 'review':
        $orderId = (int)($_GET['order_id'] ?? 0);
        (new OrderController())->showReviewForm($orderId); break;

    case 'messages':
        (new MessageController())->showMessages(); break;

    case 'notifications':
        (new NotificationController())->showNotifications(); break;

    case 'nego':
        $productId = (int)($_GET['product_id'] ?? 0);
        (new NegotiationController())->showNego($productId); break;

    case 'cara-jualan':
        render('cara-jualan', ['title' => 'Cara Jualan - ' . APP_NAME]); break;

    case 'cara-belanja':
        render('cara-belanja', ['title' => 'Cara Belanja - ' . APP_NAME]); break;

    case 'keamanan':
        render('keamanan', ['title' => 'Keamanan - ' . APP_NAME]); break;

    case 'wallet':
        requireLogin();
        $userModel = new UserModel();
        $user = $userModel->findById($_SESSION['user_id']);
        render('wallet', ['title' => 'Wallet - ' . APP_NAME, 'user' => $user]); break;

    default:
        http_response_code(404);
        render('partials/404', ['title' => '404 - ' . APP_NAME]);
}
