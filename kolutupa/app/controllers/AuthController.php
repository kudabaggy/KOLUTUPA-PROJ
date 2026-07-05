<?php
// app/controllers/AuthController.php

class AuthController {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showLogin(): void {
        if (isLoggedIn()) redirect('index.php');
        render('auth/login', ['title' => 'Masuk - ' . APP_NAME]);
    }

    public function showRegister(): void {
        if (isLoggedIn()) redirect('index.php');
        render('auth/register', ['title' => 'Daftar - ' . APP_NAME]);
    }

    public function login(): void {
        $errors = [];
        $identifier = sanitize($_POST['identifier'] ?? '');
        $password   = $_POST['password'] ?? '';

        if (empty($identifier)) $errors[] = 'Username atau email wajib diisi.';
        if (empty($password))   $errors[] = 'Password wajib diisi.';

        if (empty($errors)) {
            $user = $this->userModel->findByEmail($identifier)
                 ?? $this->userModel->findByUsername($identifier);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['avatar']    = $user['avatar'];
                redirect('index.php');
            }
            $errors[] = 'Username/email atau password salah.';
        }

        render('auth/login', ['title' => 'Masuk - ' . APP_NAME, 'errors' => $errors]);
    }


    public function register(): void {
        $errors = [];
        $name     = sanitize($_POST['name'] ?? '');
        $email    = sanitize($_POST['email'] ?? '');
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name))     $errors[] = 'Nama lengkap wajib diisi.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
        if (empty($username) || strlen($username) < 3) $errors[] = 'Username minimal 3 karakter.';
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) $errors[] = 'Username hanya boleh huruf, angka, underscore.';
        if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';

        if (empty($errors)) {
            if ($this->userModel->findByEmail($email)) $errors[] = 'Email sudah digunakan.';
            if ($this->userModel->findByUsername($username)) $errors[] = 'Username sudah digunakan.';
        }

        if (empty($errors)) {
            $id = $this->userModel->create([
                'name'     => $name,
                'email'    => $email,
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            $_SESSION['user_id']   = $id;
            $_SESSION['username']  = $username;
            $_SESSION['user_name'] = $name;
            $_SESSION['avatar']    = null;
            redirect('index.php');
        }

        render('auth/register', [
            'title'  => 'Daftar - ' . APP_NAME,
            'errors' => $errors,
            'old'    => compact('name', 'email', 'username'),
        ]);
    }

    public function logout(): void {
        session_destroy();
        redirect('index.php?page=login');
    }
}
