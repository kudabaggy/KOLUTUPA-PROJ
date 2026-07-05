<?php
// app/controllers/ProductController.php

class ProductController {
    private ProductModel $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function showCategory(string $category): void {
        $filters = [
            'size'      => sanitize($_GET['size'] ?? ''),
            'condition' => sanitize($_GET['condition'] ?? ''),
            'min_price' => (int)($_GET['min_price'] ?? 0) ?: null,
            'max_price' => (int)($_GET['max_price'] ?? 0) ?: null,
            'brand'     => sanitize($_GET['brand'] ?? ''),
        ];
        $products = $this->productModel->getByCategory($category, array_filter($filters));
        $label = $category === 'Negotiable' || $category === 'Sale'
            ? 'Negotiable'
            : ucfirst($category);
        render('products/category', [
            'title'    => "{$label} - " . APP_NAME,
            'products' => $products,
            'category' => $category,
            'label'    => $label,
            'filters'  => $filters,
        ]);
    }

    public function showProduct(int $id): void {
        $product = $this->productModel->getWithSeller($id);
        if (!$product) { http_response_code(404); render('partials/404'); return; }

        $images       = $this->productModel->getAllImages($id);
        $measurements = $this->productModel->getMeasurements($id);
        $isLiked      = isLoggedIn() ? $this->productModel->isLiked($_SESSION['user_id'], $id) : false;

        $reviewModel = new ReviewModel();
        $reviews     = $reviewModel->getForProduct($id, 10);
        $breakdown   = $reviewModel->getRatingBreakdownForProduct($id);
        $reviewScore = $reviewModel->getReviewScoreForProduct($id);

        $relatedProducts = $this->productModel->getBySellerWithImages($product['seller_id']);
        $relatedProducts = array_filter($relatedProducts, fn($p) => $p['id'] != $id);
        $relatedProducts = array_slice($relatedProducts, 0, 5);

        render('products/detail', [
            'title'          => $product['title'] . ' - ' . APP_NAME,
            'product'        => $product,
            'images'         => $images,
            'measurements'   => $measurements,
            'isLiked'        => $isLiked,
            'reviews'        => $reviews,
            'breakdown'      => $breakdown,
            'reviewScore'    => $reviewScore,
            'relatedProducts'=> $relatedProducts,
        ]);
    }

    public function showAddProduct(): void {
        requireLogin();
        render('products/add', ['title' => 'Tambah Produk - ' . APP_NAME]);
    }

    public function showEditProduct(int $id): void {
        requireLogin();
        $product = $this->productModel->findById($id);
        if (!$product || $product['seller_id'] !== $_SESSION['user_id'] || ($product['status'] ?? 'active') === 'sold') {
            $_SESSION['flash'] = 'Produk sold out tidak bisa diedit.';
            redirect('index.php');
            return;
        }

        $images       = $this->productModel->getAllImages($id);
        $measurements = $this->productModel->getMeasurements($id);

        render('products/edit', [
            'title'        => 'Edit Produk - ' . APP_NAME,
            'product'      => $product,
            'images'       => $images,
            'measurements' => $measurements,
        ]);
    }

    public function addProduct(): void {
        requireLogin();
        $errors = [];
        $title       = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $category    = sanitize($_POST['category'] ?? '');
        $condition   = sanitize($_POST['condition_item'] ?? '');
        $size        = sanitize($_POST['size'] ?? '');
        $color       = sanitize($_POST['color'] ?? '');
        $brand       = sanitize($_POST['brand'] ?? '');
        $price       = (int)($_POST['price'] ?? 0);
        $negotiable  = isset($_POST['negotiable']) ? 1 : 0;

        if (empty($title))     $errors[] = 'Judul wajib diisi.';
        if ($price <= 0)       $errors[] = 'Harga harus lebih dari 0.';
        if (empty($category))  $errors[] = 'Kategori wajib dipilih.';
        if (empty($condition)) $errors[] = 'Kondisi wajib dipilih.';

        $uploadedPaths = [];
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
                if (!$tmp) continue;
                $result = uploadImage($tmp, $_FILES['images']['name'][$i]);
                if ($result['success']) {
                    $uploadedPaths[] = $result['path'];
                } else {
                    $errors[] = $result['error'];
                }
            }
        }

        if (empty($errors)) {
            $productId = $this->productModel->create([
                'seller_id'      => $_SESSION['user_id'],
                'title'          => $title,
                'description'    => $description,
                'category'       => $category,
                'brand'          => $brand,
                'condition_item' => $condition,
                'size'           => $size,
                'color'          => $color,
                'price'          => $price,
                'is_negotiable'  => $negotiable,
                'status'         => isset($_POST['draft']) ? 'draft' : 'active',
            ]);

            if ($uploadedPaths) {
                $this->productModel->saveImages($productId, $uploadedPaths);
            }

            $measurements = [];
            if (!empty($_POST['meas_label'])) {
                foreach ($_POST['meas_label'] as $k => $label) {
                    if ($label && isset($_POST['meas_value'][$k])) {
                        $measurements[$label] = $_POST['meas_value'][$k];
                    }
                }
                if ($measurements) $this->productModel->saveMeasurements($productId, $measurements);
            }

            redirect("index.php?page=product&id={$productId}");
        }

        render('products/add', ['title' => 'Tambah Produk - ' . APP_NAME, 'errors' => $errors]);
    }

    public function updateProduct(): void {
        requireLogin();
        $productId   = (int)($_POST['product_id'] ?? 0);
        $product     = $this->productModel->findById($productId);
        if (!$product || $product['seller_id'] !== $_SESSION['user_id'] || ($product['status'] ?? 'active') === 'sold') {
            $_SESSION['flash'] = 'Produk sold out tidak bisa diedit.';
            redirect('index.php');
            return;
        }

        $errors = [];
        $title       = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $category    = sanitize($_POST['category'] ?? '');
        $condition   = sanitize($_POST['condition_item'] ?? '');
        $size        = sanitize($_POST['size'] ?? '');
        $color       = sanitize($_POST['color'] ?? '');
        $brand       = sanitize($_POST['brand'] ?? '');
        $price       = (int)($_POST['price'] ?? 0);
        $negotiable  = isset($_POST['negotiable']) ? 1 : 0;

        if (empty($title))     $errors[] = 'Judul wajib diisi.';
        if ($price <= 0)       $errors[] = 'Harga harus lebih dari 0.';
        if (empty($category))  $errors[] = 'Kategori wajib dipilih.';
        if (empty($condition)) $errors[] = 'Kondisi wajib dipilih.';

        $uploadedPaths = [];
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
                if (!$tmp) continue;
                $result = uploadImage($tmp, $_FILES['images']['name'][$i]);
                if ($result['success']) {
                    $uploadedPaths[] = $result['path'];
                } else {
                    $errors[] = $result['error'];
                }
            }
        }

        if (empty($errors)) {
            $this->productModel->update($productId, [
                'title'          => $title,
                'description'    => $description,
                'category'       => $category,
                'brand'          => $brand,
                'condition_item' => $condition,
                'size'           => $size,
                'color'          => $color,
                'price'          => $price,
                'is_negotiable'  => $negotiable,
            ]);

            if ($uploadedPaths) {
                $this->productModel->saveImages($productId, $uploadedPaths);
            }

            $measurements = [];
            if (!empty($_POST['meas_label'])) {
                foreach ($_POST['meas_label'] as $k => $label) {
                    if ($label && isset($_POST['meas_value'][$k])) {
                        $measurements[$label] = $_POST['meas_value'][$k];
                    }
                }
            }
            $this->productModel->saveMeasurements($productId, $measurements);

            redirect("index.php?page=product&id={$productId}");
            return;
        }

        $images       = $this->productModel->getAllImages($productId);
        $measurements = $this->productModel->getMeasurements($productId);
        render('products/edit', [
            'title'        => 'Edit Produk - ' . APP_NAME,
            'errors'       => $errors,
            'product'      => array_merge($product, [
                'title'          => $title,
                'description'    => $description,
                'category'       => $category,
                'condition_item' => $condition,
                'size'           => $size,
                'color'          => $color,
                'brand'          => $brand,
                'price'          => $price,
                'is_negotiable'  => $negotiable,
            ]),
            'images'       => $images,
            'measurements' => $measurements,
        ]);
    }

    public function toggleLike(): void {
        requireLogin();
        $productId = (int)($_POST['product_id'] ?? 0);
        $liked = $this->productModel->toggleLike($_SESSION['user_id'], $productId);
        echo json_encode(['liked' => $liked]);
        exit;
    }

    public function showFavorites(): void {
        requireLogin();
        $likedProducts = $this->productModel->getLikedByUser($_SESSION['user_id']);
        render('products/favorites', [
            'title'    => 'Produk Favorit - ' . APP_NAME,
            'products' => $likedProducts,
        ]);
    }

    public function search(): void
{
    $q = trim($_GET['q'] ?? '');

    if (strlen($q) > 100) {
        $q = substr($q, 0, 100);
    }

    $products = [];

    if (strlen($q) >= 2) {
        $products = $this->productModel->search($q);
    }

    render('products/search', [
        'title'    => $q
            ? "Hasil pencarian: {$q} - " . APP_NAME
            : "Cari Produk - " . APP_NAME,
        'products' => $products,
        'q'        => $q,
    ]);
}
}
