# KOLUTUPA - PHP Marketplace App
## Panduan Instalasi & Deployment XAMPP

---

## 📋 Persyaratan Sistem
- XAMPP 8.0+ (PHP 8.0+, MySQL 8.0+, Apache 2.4+)
- Browser modern (Chrome, Firefox, Edge)
- min. 256MB RAM

---

## 🚀 Langkah Instalasi di XAMPP

### 1. Copy Project
```
Salin folder `kolutupa` ke:
C:\xampp\htdocs\kolutupa\        (Windows)
/opt/lampp/htdocs/kolutupa/      (Linux/macOS)
```

### 2. Buat Database MySQL
Buka browser → http://localhost/phpmyadmin

a) Klik **"New"** di sidebar kiri
b) Nama database: `kolutupa`
c) Collation: `utf8mb4_unicode_ci`
d) Klik **"Create"**

### 3. Import SQL
a) Klik database `kolutupa` di sidebar
b) Klik tab **"Import"**
c) Klik **"Choose File"** → pilih `kolutupa/database/kolutupa.sql`
d) Klik **"Go"**

### 4. Konfigurasi Database
Edit file `kolutupa/config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // username MySQL XAMPP default
define('DB_PASS', '');           // password MySQL XAMPP default (kosong)
define('DB_NAME', 'kolutupa');
define('BASE_URL', 'http://localhost/kolutupa/public/');
```

### 5. Enable mod_rewrite (Apache)
a) Buka XAMPP Control Panel
b) Klik **"Config"** di baris Apache → pilih **"httpd.conf"**
c) Cari baris: `#LoadModule rewrite_module modules/mod_rewrite.so`
d) Hapus tanda `#` → Simpan
e) Cari `AllowOverride None` (di bagian htdocs) → ubah jadi `AllowOverride All`
f) Restart Apache

### 6. Buat Folder Upload
Pastikan folder berikut ada dan writable:
```
kolutupa/public/uploads/
kolutupa/public/uploads/products/
```
Windows: klik kanan → Properties → Security → beri izin write
Linux: `chmod 755 kolutupa/public/uploads/`

### 7. Akses Aplikasi
Buka browser → **http://localhost/kolutupa/public/**

---

## 👤 Akun Demo (Password: `password`)
| Username | Email | Role |
|----------|-------|------|
| koltups | koltups@kolutupa.com | Seller (KOLTUPS) |
| hfrtm_store | hfrtm@kolutupa.com | Seller (HFRTM STORE) |
| rasya_labrador | rasya@example.com | Buyer |

---

## 📁 Struktur Folder
```
kolutupa/
├── app/
│   ├── controllers/       # Business logic
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   └── OtherControllers.php
│   ├── models/            # Database layer
│   │   ├── Model.php      # Base model
│   │   ├── UserModel.php
│   │   ├── ProductModel.php
│   │   └── OtherModels.php
│   ├── views/             # HTML templates
│   │   ├── partials/      # header, footer, card
│   │   ├── auth/          # login, register
│   │   ├── profile/       # show, settings
│   │   ├── products/      # detail, category, add
│   │   ├── orders/        # cart, checkout, payment
│   │   ├── messages/
│   │   └── notifications/
│   └── helpers.php        # Global helper functions
├── assets/
│   ├── css/style.css      # Main stylesheet
│   └── js/app.js          # Main JavaScript
├── config/
│   ├── config.php         # App configuration
│   └── Database.php       # PDO singleton
├── database/
│   └── kolutupa.sql       # SQL schema + seed data
└── public/
    ├── index.php          # Front controller (router)
    ├── .htaccess          # Apache URL rewriting
    ├── assets/            # Served CSS/JS/images
    └── uploads/           # User-uploaded files
```

---

## ⚙️ Fitur Lengkap
- ✅ Autentikasi (Login/Register/Logout) + bcrypt password
- ✅ Profil toko (avatar, bio, follower, following)
- ✅ Upload produk multi-foto + measurements
- ✅ Kategori & filter (Pria/Wanita/Branded/Sale)
- ✅ Cart & checkout flow
- ✅ Sistem pembayaran (E-Wallet, QR, Transfer Bank)
- ✅ Negosiasi harga
- ✅ Pesan/Chat antar pengguna
- ✅ Notifikasi real-time (polling)
- ✅ Review & rating sistem
- ✅ CSRF protection
- ✅ XSS sanitization
- ✅ Responsive mobile design
- ✅ SEO-friendly URL structure

---

## 🔧 Troubleshooting

**Error "Page Not Found"**
→ Aktifkan mod_rewrite Apache (lihat langkah 5)

**Error "Database connection failed"**
→ Pastikan MySQL aktif di XAMPP dan konfigurasi di `config.php` benar

**Gambar tidak muncul**
→ Cek permission folder `public/uploads/` (harus writable)

**Session tidak bekerja**
→ Cek `session_save_path` di `php.ini` XAMPP

---

## 📞 Kontak
Untuk bug report atau pertanyaan, silakan buat issue di repository.
