<?php
// app/controllers/ProfileController.php

class ProfileController {
    private UserModel $userModel;
    private ProductModel $productModel;

    public function __construct() {
        $this->userModel   = new UserModel();
        $this->productModel = new ProductModel();
    }

    public function showProfile(?string $username = null): void {
        // If username is provided, look up that user; otherwise show logged-in user's profile
        if ($username) {
            $user = $this->userModel->findByUsername($username);
            if (!$user) {
                http_response_code(404);
                render('partials/404');
                return;
            }
        } else {
            requireLogin();
            $user = $this->userModel->findById($_SESSION['user_id']);
            if (!$user) {
                http_response_code(404);
                render('partials/404');
                return;
            }
        }

        $products    = $this->productModel->getBySellerWithImages($user['id']);
        $liked       = $this->productModel->getLikedByUser($user['id']);
        $followers   = $this->userModel->getFollowersCount($user['id']);
        $following   = $this->userModel->getFollowingCount($user['id']);
        $isOwner     = isLoggedIn() && $_SESSION['user_id'] == $user['id'];
        $isFollowing = isLoggedIn() && !$isOwner
            ? $this->userModel->isFollowing($_SESSION['user_id'], $user['id'])
            : false;

        $reviewModel = new ReviewModel();
        if ($isOwner) {
            $reviews     = $reviewModel->getForSeller($user['id'], 5);
            $breakdown   = $reviewModel->getRatingBreakdown($user['id']);
            $reviewScore = $this->userModel->getReviewScore($user['id']);
            $reviewMode  = 'received';
        } else {
            $reviews     = $reviewModel->getForSeller($user['id'], 5);
            $breakdown   = $reviewModel->getRatingBreakdown($user['id']);
            $reviewScore = $this->userModel->getReviewScore($user['id']);
            $reviewMode  = 'received';
        }

        render('profile/show', [
            'title'       => $user['name'] . ' - ' . APP_NAME,
            'user'        => $user,
            'products'    => $products,
            'liked'       => $liked,
            'followers'   => $followers,
            'following'   => $following,
            'isOwner'     => $isOwner,
            'isFollowing' => $isFollowing,
            'reviews'     => $reviews,
            'breakdown'   => $breakdown,
            'reviewScore' => $reviewScore,
            'reviewMode'  => $reviewMode,
        ]);
    }

    public function showSettings(): void {
        requireLogin();
        $user    = $this->userModel->findById($_SESSION['user_id']);
        $address = $this->userModel->getSellerAddress($_SESSION['user_id']);
        render('profile/settings', [
            'title'   => 'Profil - ' . APP_NAME,
            'user'    => $user,
            'address' => $address,
            'tab'     => sanitize($_GET['tab'] ?? 'profile'),
        ]);
    }

    public function updateProfile(): void {
        requireLogin();
        $errors = [];
        $name    = sanitize($_POST['name'] ?? '');
        $bio     = sanitize($_POST['bio'] ?? '');
        $website = sanitize($_POST['website'] ?? '');

        if (empty($name)) $errors[] = 'Nama tidak boleh kosong.';

        $data = ['name' => $name, 'bio' => $bio, 'website' => $website];

        if (!empty($_FILES['avatar']['tmp_name'])) {
            $result = uploadImage($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name']);
            if ($result['success']) {
                $data['avatar'] = $result['path'];
                $_SESSION['avatar'] = $result['path'];
            } else {
                $errors[] = $result['error'];
            }
        }

        if (empty($errors)) {
            $this->userModel->update($_SESSION['user_id'], $data);
            $_SESSION['user_name'] = $name;
            $_SESSION['flash'] = 'Profil berhasil diperbarui.';
            redirect('index.php?page=settings&tab=profile');
        }

        $user    = $this->userModel->findById($_SESSION['user_id']);
        $address = $this->userModel->getSellerAddress($_SESSION['user_id']);
        render('profile/settings', ['title' => 'Profil - ' . APP_NAME, 'user' => $user, 'address' => $address, 'errors' => $errors, 'tab' => 'profile']);
    }

    public function updateAccount(): void {
        requireLogin();
        $errors = [];
        $email    = sanitize($_POST['email'] ?? '');
        $username = sanitize($_POST['username'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
        if (strlen($username) < 3) $errors[] = 'Username minimal 3 karakter.';

        $data = ['email' => $email, 'username' => $username];

        if (!empty($_POST['new_password'])) {
            if (strlen($_POST['new_password']) < 6) $errors[] = 'Password minimal 6 karakter.';
            else $data['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        }

        if (empty($errors)) {
            $this->userModel->update($_SESSION['user_id'], $data);
            $_SESSION['username'] = $username;
            $_SESSION['flash'] = 'Akun berhasil diperbarui.';
            redirect('index.php?page=settings&tab=akun');
        }

        $user = $this->userModel->findById($_SESSION['user_id']);
        $address = $this->userModel->getSellerAddress($_SESSION['user_id']);
        render('profile/settings', ['title' => 'Profil - ' . APP_NAME, 'user' => $user, 'address' => $address, 'errors' => $errors, 'tab' => 'akun']);
    }

    public function updateAddress(): void {
        requireLogin();
        $data = [
            'recipient_name' => sanitize($_POST['recipient_name'] ?? ''),
            'phone'          => sanitize($_POST['phone'] ?? ''),
            'full_address'   => sanitize($_POST['full_address'] ?? ''),
            'city'           => sanitize($_POST['city'] ?? ''),
            'province'       => sanitize($_POST['province'] ?? ''),
            'postal_code'    => sanitize($_POST['postal_code'] ?? ''),
        ];
        $this->userModel->updateSellerAddress($_SESSION['user_id'], $data);
        $_SESSION['flash'] = 'Alamat berhasil diperbarui.';
        redirect('index.php?page=settings&tab=alamat');
    }

    public function toggleFollow(): void {
        requireLogin();
        $targetId = (int)($_POST['user_id'] ?? 0);
        if ($targetId === $_SESSION['user_id']) { echo json_encode(['error' => 'Invalid']); exit; }
        if ($this->userModel->isFollowing($_SESSION['user_id'], $targetId)) {
            $this->userModel->unfollow($_SESSION['user_id'], $targetId);
            echo json_encode(['following' => false]);
        } else {
            $this->userModel->follow($_SESSION['user_id'], $targetId);
            echo json_encode(['following' => true]);
        }
        exit;
    }
}

// ─── OrderController ─────────────────────────────────────────────────────────

class OrderController {
    private OrderModel $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function showOrders(): void {
        requireLogin();
        $tab    = sanitize($_GET['tab'] ?? 'terjual');
        $status = sanitize($_GET['status'] ?? '');
        $role   = $tab === 'terjual' ? 'seller' : 'buyer';
        $orders = $this->orderModel->getByUser($_SESSION['user_id'], $role, $status);
        render('orders/list', ['title' => 'Pesanan - ' . APP_NAME, 'orders' => $orders, 'tab' => $tab, 'status' => $status]);
    }

    public function showOrderDetail(int $orderId): void {
        requireLogin();
        $order = $this->orderModel->findById($orderId);
        if (!$order) { redirect('index.php?page=orders&tab=dibeli'); return; }

        $isSeller = $order['seller_id'] == $_SESSION['user_id'];
        $isBuyer  = $order['buyer_id'] == $_SESSION['user_id'];
        if (!$isSeller && !$isBuyer) { redirect('index.php'); return; }

        $productModel = new ProductModel();
        $product = $productModel->findById($order['product_id']);
        $seller = (new UserModel())->findById($order['seller_id']);
        $buyer  = (new UserModel())->findById($order['buyer_id']);

        render('orders/detail', [
            'title'   => 'Detail Pesanan - ' . APP_NAME,
            'order'   => $order,
            'product' => $product,
            'seller'  => $seller,
            'buyer'   => $buyer,
            'isSeller'=> $isSeller,
            'isBuyer' => $isBuyer,
        ]);
    }

    public function checkout(int $productId): void {
        requireLogin();
        $productModel = new ProductModel();
        $product = $productModel->getWithSeller($productId);
        if (!$product) { redirect('index.php'); return; }

        $negotiation = null;
        $negoId = (int)($_GET['nego_id'] ?? 0);
        if ($negoId) {
            $negotiationModel = new NegotiationModel();
            $nego = $negotiationModel->findById($negoId);
            if ($nego && $nego['buyer_id'] === $_SESSION['user_id'] && $nego['product_id'] === $productId && $nego['status'] === 'accepted') {
                $orderModel = new OrderModel();
                if ($orderModel->hasActiveOrderForBuyerProduct($_SESSION['user_id'], $productId)) {
                    $_SESSION['flash'] = 'Pesanan untuk negosiasi ini sudah dibuat.';
                    redirect('index.php?page=orders&tab=dibeli');
                    return;
                }
                $negotiation = $nego;
                $product['negotiated_price'] = $nego['offered_price'];
            }
        }

        render('orders/checkout', [
            'title' => 'Checkout - ' . APP_NAME,
            'product' => $product,
            'negotiation' => $negotiation,
        ]);
    }

    public function placeOrder(): void {
        requireLogin();
        $productId     = (int)($_POST['product_id'] ?? 0);
        $productModel  = new ProductModel();
        $product       = $productModel->getWithSeller($productId);
        if (!$product) { redirect('index.php'); return; }

        $productPrice = $product['price'];
        $negoId = (int)($_POST['nego_id'] ?? 0);
        if ($negoId) {
            $negotiationModel = new NegotiationModel();
            $nego = $negotiationModel->findById($negoId);
            if ($nego && $nego['buyer_id'] === $_SESSION['user_id'] && $nego['product_id'] === $productId && $nego['status'] === 'accepted') {
                $productPrice = $nego['offered_price'];
            } else {
                redirect('index.php');
                return;
            }
        }

        $orderId = $this->orderModel->createOrder([
            'buyer_id'         => $_SESSION['user_id'],
            'seller_id'        => $product['seller_id'],
            'product_id'       => $productId,
            'product_price'    => $productPrice,
            'shipping_cost'    => 20000,
            'total_amount'     => $productPrice + 20000,
            'recipient_name'   => sanitize($_POST['recipient_name'] ?? ''),
            'recipient_phone'  => sanitize($_POST['recipient_phone'] ?? ''),
            'shipping_address' => sanitize($_POST['shipping_address'] ?? ''),
            'shipping_detail'  => sanitize($_POST['shipping_detail'] ?? ''),
        ]);

        $notifModel = new NotificationModel();
        $notifModel->createNotification(
            $product['seller_id'],
            $_SESSION['user_id'],
            $productId,
            'system',
            'Ada pesanan baru untuk produk "' . $product['title'] . '". Silakan cek halaman pesanan Anda.'
        );

        redirect("index.php?page=payment&order_id={$orderId}");
    }

    public function showPayment(int $orderId): void {
        requireLogin();
        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['buyer_id'] != $_SESSION['user_id']) { redirect('index.php'); return; }
        if (!empty($order['payment_method'])) {
            redirect('index.php?page=orders&tab=dibeli');
            return;
        }
        $productModel = new ProductModel();
        $product = $productModel->getWithSeller($order['product_id']);
        render('orders/payment', ['title' => 'Pembayaran - ' . APP_NAME, 'order' => $order, 'product' => $product]);
    }

    public function approveOrder(): void {
        requireLogin();
        $orderId = (int)($_POST['order_id'] ?? 0);
        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['seller_id'] != $_SESSION['user_id']) {
            redirect('index.php');
            return;
        }

        if ($order['status'] !== 'pending' || empty($order['payment_method'])) {
            redirect('index.php?page=orders&tab=terjual');
            return;
        }

        $this->orderModel->update($orderId, ['status' => 'dalam_proses']);

        $notifModel = new NotificationModel();
        $notifModel->createNotification(
            $order['buyer_id'],
            $_SESSION['user_id'],
            $order['product_id'],
            'system',
            'Pesanan Anda telah disetujui penjual dan sedang diproses.'
        );

        $_SESSION['flash'] = 'Pesanan berhasil disetujui.';
        redirect('index.php?page=orders&tab=terjual');
    }

    public function confirmReceipt(): void {
        requireLogin();
        $orderId = (int)($_POST['order_id'] ?? 0);
        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['buyer_id'] != $_SESSION['user_id']) {
            redirect('index.php');
            return;
        }

        if ($order['status'] !== 'dalam_proses') {
            redirect('index.php?page=orders&tab=dibeli');
            return;
        }

        $this->orderModel->update($orderId, ['status' => 'selesai']);

        $userModel = new UserModel();
        $userModel->recalculateWalletBalance($order['seller_id']);
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $order['seller_id']) {
            $updatedUser = $userModel->findById($order['seller_id']);
            $_SESSION['wallet_balance'] = (float)($updatedUser['wallet_balance'] ?? 0);
        }

        $productModel = new ProductModel();
        $product = $productModel->findById($order['product_id']);
        $productModel->update($order['product_id'], ['status' => 'sold']);

        $notifModel = new NotificationModel();
        $notifModel->createNotification(
            $order['seller_id'],
            $_SESSION['user_id'],
            $order['product_id'],
            'system',
            'Pesanan untuk produk "' . ($product['title'] ?? 'produk') . '" telah selesai dan diterima pembeli.'
        );

        $_SESSION['flash'] = 'Terima kasih! Pesanan telah selesai.';
        redirect('index.php?page=orders&tab=dibeli');
    }

    public function showReviewForm(int $orderId): void {
        requireLogin();
        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['buyer_id'] != $_SESSION['user_id'] || $order['status'] !== 'selesai') {
            redirect('index.php?page=orders&tab=dibeli');
            return;
        }

        $reviewModel = new ReviewModel();
        if ($reviewModel->hasReviewForOrder($orderId)) {
            redirect('index.php?page=orders&tab=dibeli');
            return;
        }

        $productModel = new ProductModel();
        $product = $productModel->findById($order['product_id']);
        if (!$product) {
            redirect('index.php?page=orders&tab=dibeli');
            return;
        }

        $userModel = new UserModel();
        $seller = $userModel->findById($product['seller_id']);
        $product['seller_name'] = $seller['name'] ?? $seller['username'] ?? 'Penjual';
        $product['seller_avatar'] = $seller['avatar'] ?? null;
        $product['seller_city'] = $seller['city'] ?? null;
        $product['primary_image'] = $productModel->getPrimaryImage($product['id']);

        render('orders/review', ['title' => 'Ulas Pesanan - ' . APP_NAME, 'order' => $order, 'product' => $product]);
    }

    public function submitReview(): void {
        requireLogin();
        $orderId = (int)($_POST['order_id'] ?? 0);
        $rating  = (int)($_POST['rating'] ?? 0);
        $comment = sanitize($_POST['comment'] ?? '');

        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['buyer_id'] != $_SESSION['user_id'] || $order['status'] !== 'selesai') {
            redirect('index.php?page=orders&tab=dibeli');
            return;
        }

        $reviewModel = new ReviewModel();
        if ($reviewModel->hasReviewForOrder($orderId)) {
            redirect('index.php?page=orders&tab=dibeli');
            return;
        }

        if ($rating < 1 || $rating > 5) {
            $_SESSION['flash'] = 'Silakan pilih rating antara 1 dan 5.';
            redirect("index.php?page=review&order_id={$orderId}");
            return;
        }

        $reviewModel->create([
            'order_id'    => $orderId,
            'reviewer_id' => $_SESSION['user_id'],
            'seller_id'   => $order['seller_id'],
            'rating'      => $rating,
            'comment'     => $comment,
        ]);

        $_SESSION['flash'] = 'Terima kasih atas ulasan Anda!';
        redirect('index.php?page=orders&tab=dibeli');
    }
}

// ─── CartController ───────────────────────────────────────────────────────────

class CartController {
    private CartModel $cartModel;

    public function __construct() {
        $this->cartModel = new CartModel();
    }

    public function showCart(): void {
        requireLogin();
        $items = $this->cartModel->getCartByUser($_SESSION['user_id']);
        $total = array_sum(array_column($items, 'price'));
        render('orders/cart', ['title' => 'Keranjang - ' . APP_NAME, 'items' => $items, 'total' => $total]);
    }

    public function addToCart(): void {
        requireLogin();
        $productId = (int)($_POST['product_id'] ?? 0);

        $productModel = new ProductModel();
        $product = $productModel->getWithSeller($productId);
        if (!$product) {
            $_SESSION['flash'] = 'Produk ini sudah tidak tersedia untuk dibeli.';
            redirect('index.php');
            return;
        }

        $this->cartModel->addToCart($_SESSION['user_id'], $productId);
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo json_encode(['success' => true]);
            exit;
        }
        redirect("index.php?page=cart");
    }

    public function removeFromCart(): void {
        requireLogin();
        $productId = (int)($_POST['product_id'] ?? 0);
        $this->cartModel->removeFromCart($_SESSION['user_id'], $productId);
        redirect("index.php?page=cart");
    }
}

// ─── MessageController ────────────────────────────────────────────────────────

class MessageController {
    private MessageModel $messageModel;

    public function __construct() {
        $this->messageModel = new MessageModel();
    }

    public function showMessages(): void {
        requireLogin();
        $otherId       = (int)($_GET['user'] ?? 0);
        $conversations = $this->messageModel->getConversations($_SESSION['user_id']);
        $thread        = $otherId ? $this->messageModel->getThread($_SESSION['user_id'], $otherId) : [];
        $otherUser     = $otherId ? (new UserModel())->findById($otherId) : null;
        if ($otherId) $this->messageModel->markRead($_SESSION['user_id'], $otherId);

        $negotiation = null;
        $orderExists = false;
        if ($otherId) {
            $negotiation = (new NegotiationModel())->getLatestBetweenUsers($_SESSION['user_id'], $otherId);
            if ($negotiation && $negotiation['status'] === 'accepted' && $negotiation['buyer_id'] === $_SESSION['user_id']) {
                $orderExists = (new OrderModel())->hasActiveOrderForBuyerProduct($_SESSION['user_id'], $negotiation['product_id']);
            }
        }

        render('messages/index', [
            'title'         => 'Pesan - ' . APP_NAME,
            'conversations' => $conversations,
            'thread'        => $thread,
            'otherUser'     => $otherUser,
            'otherId'       => $otherId,
            'negotiation'   => $negotiation,
            'orderExists'   => $orderExists,
        ]);
    }

    public function sendMessage(): void {
        requireLogin();
        $receiverId = (int)($_POST['receiver_id'] ?? 0);
        $content    = sanitize($_POST['content'] ?? '');
        if ($receiverId && $content) {
            $this->messageModel->send($_SESSION['user_id'], $receiverId, $content);
        }
        redirect("index.php?page=messages&user={$receiverId}");
    }
}

// ─── NotificationController ───────────────────────────────────────────────────

class NotificationController {
    private NotificationModel $notifModel;

    public function __construct() {
        $this->notifModel = new NotificationModel();
    }

    public function showNotifications(): void {
        requireLogin();
        $notifications = $this->notifModel->getForUser($_SESSION['user_id']);
        $this->notifModel->markAllRead($_SESSION['user_id']);
        render('notifications/index', ['title' => 'Notifikasi - ' . APP_NAME, 'notifications' => $notifications]);
    }
}

// ─── NegotiationController ────────────────────────────────────────────────────

class NegotiationController {
    private NegotiationModel $negoModel;

    public function __construct() {
        $this->negoModel = new NegotiationModel();
    }

    public function showNego(int $productId): void {
        requireLogin();
        $productModel = new ProductModel();
        $product = $productModel->getWithSeller($productId);
        if (!$product) { redirect('index.php'); return; }
        render('products/nego', ['title' => 'Nego - ' . APP_NAME, 'product' => $product]);
    }

    public function submitNego(): void {
        requireLogin();
        $productId   = (int)($_POST['product_id'] ?? 0);
        $offeredPrice = (int)($_POST['offered_price'] ?? 0);
        $productModel = new ProductModel();
        $product = $productModel->getWithSeller($productId);
        if (!$product || $offeredPrice <= 0) { redirect("index.php?page=product&id={$productId}"); return; }

        $this->negoModel->create([
            'product_id'    => $productId,
            'buyer_id'      => $_SESSION['user_id'],
            'seller_id'     => $product['seller_id'],
            'offered_price' => $offeredPrice,
        ]);
        $_SESSION['flash'] = 'Penawaran berhasil dikirim! Lanjutkan diskusi melalui chat.';
        redirect("index.php?page=messages&user={$product['seller_id']}");
    }

    public function acceptNego(): void {
        requireLogin();
        $negoId = (int)($_POST['nego_id'] ?? 0);
        $nego = $this->negoModel->findById($negoId);
        if (!$nego || $nego['seller_id'] != $_SESSION['user_id'] || $nego['status'] !== 'pending') {
            redirect('index.php?page=messages');
            return;
        }

        $this->negoModel->update($negoId, ['status' => 'accepted']);
        $product = (new ProductModel())->findById($nego['product_id']);
        (new MessageModel())->send($_SESSION['user_id'], $nego['buyer_id'], "Penawaran nego untuk '{$product['title']}' disetujui dengan harga " . formatRupiah($nego['offered_price']) . ".");
        $_SESSION['flash'] = 'Penawaran berhasil disetujui.';
        redirect("index.php?page=messages&user={$nego['buyer_id']}");
    }

    public function rejectNego(): void {
        requireLogin();
        $negoId = (int)($_POST['nego_id'] ?? 0);
        $nego = $this->negoModel->findById($negoId);
        if (!$nego || $nego['seller_id'] != $_SESSION['user_id'] || $nego['status'] !== 'pending') {
            redirect('index.php?page=messages');
            return;
        }

        $this->negoModel->update($negoId, ['status' => 'rejected']);
        $product = (new ProductModel())->findById($nego['product_id']);
        (new MessageModel())->send($_SESSION['user_id'], $nego['buyer_id'], "Penawaran nego untuk '{$product['title']}' ditolak oleh penjual.");
        $_SESSION['flash'] = 'Penawaran berhasil ditolak.';
        redirect("index.php?page=messages&user={$nego['buyer_id']}");
    }
}
