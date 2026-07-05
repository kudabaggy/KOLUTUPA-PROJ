<?php
// app/helpers.php

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) redirect('index.php?page=login');
}

function redirect(string $url): void {
    header("Location: " . BASE_URL . $url);
    exit;
}

function sanitize(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function formatRupiah(float $amount): string {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function timeAgo(\DateTime|string $datetime): string {
    $now  = new DateTime();
    $past = is_string($datetime) ? new DateTime($datetime) : $datetime;
    $diff = $now->diff($past);

    if ($diff->y > 0) return $diff->y . ' tahun yang lalu';
    if ($diff->m > 0) return $diff->m . ' bulan yang lalu';
    if ($diff->d > 0) return $diff->d . ' hari yang lalu';
    if ($diff->h > 0) return $diff->h . ' jam yang lalu';
    if ($diff->i > 0) return $diff->i . ' menit yang lalu';
    return 'Baru saja';
}

function render(string $view, array $data = []): void {
    extract($data);

    if (isLoggedIn()) {
        $currentUser = (new UserModel())->findById($_SESSION['user_id']);
    } else {
        $currentUser = null;
    }

    $viewFile = BASE_PATH . "/app/views/{$view}.php";
    if (!file_exists($viewFile)) {
        die("View not found: {$view}");
    }
    require BASE_PATH . '/app/views/partials/header.php';
    require $viewFile;
    require BASE_PATH . '/app/views/partials/footer.php';
}

function uploadImage(string $tmpPath, string $originalName): array {
    $allowed   = ['jpg', 'jpeg', 'png', 'webp'];
    $maxSize   = 5 * 1024 * 1024; // 5MB
    $ext       = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'error' => 'Format gambar tidak didukung.'];
    }
    if (filesize($tmpPath) > $maxSize) {
        return ['success' => false, 'error' => 'Ukuran gambar terlalu besar (max 5MB).'];
    }

    $dir = UPLOAD_PATH . 'products/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $filename = uniqid('img_', true) . '.' . $ext;
    $dest     = $dir . $filename;

    if (!move_uploaded_file($tmpPath, $dest)) {
        return ['success' => false, 'error' => 'Gagal mengunggah gambar.'];
    }

    return ['success' => true, 'path' => 'uploads/products/' . $filename];
}

function avatarUrl(?string $avatar): string {
    if ($avatar) return BASE_URL . $avatar;
    return BASE_URL . 'assets/images/default-avatar.png';
}

function productImageUrl(?string $path): string {
    if ($path) return BASE_URL . $path;
    return BASE_URL . 'assets/images/placeholder.jpg';
}

function flash(): ?string {
    if (isset($_SESSION['flash'])) {
        $msg = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $msg;
    }
    return null;
}

function stars(float $rating): string {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= round($rating) ? '★' : '☆';
    }
    return $html;
}

function active(string $page, string $current): string {
    return $page === $current ? 'active' : '';
}

function csrf(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

function verifyCsrf(): bool {
    return isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token']);
}
