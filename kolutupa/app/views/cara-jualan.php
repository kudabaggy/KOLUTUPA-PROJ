<?php // app/views/cara-jualan.php ?>

<style>
  .cj-page { padding: 2.5rem 0 !important; font-family: inherit !important; }

  .cj-hero { text-align: center !important; margin-bottom: 3rem !important; border-bottom: 0.5px solid rgba(0,0,0,0.1) !important; padding-bottom: 2.5rem !important; }
  .cj-hero .cj-hero-tag { display: inline-block !important; font-size: 11px !important; font-weight: 500 !important; letter-spacing: 0.1em !important; text-transform: uppercase !important; color: #888 !important; border: 0.5px solid #ccc !important; border-radius: 20px !important; padding: 4px 14px !important; margin-bottom: 1rem !important; }
  .cj-hero h1 { font-size: 26px !important; font-weight: 500 !important; color: #111 !important; line-height: 1.3 !important; margin-bottom: 0.6rem !important; }
  .cj-hero p { font-size: 15px !important; color: #666 !important; max-width: 480px !important; margin: 0 auto !important; }

  /* Grid 3 kolom — 7 item = 3+3+1, card terakhir full width */
  .cj-steps-grid {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 1px !important;
    background: #e0e0e0 !important;
    border: 0.5px solid #e0e0e0 !important;
    border-radius: 12px !important;
    overflow: hidden !important;
    margin-bottom: 2.5rem !important;
  }
  .cj-steps-grid .cj-step-card:last-child {
    grid-column: 1 / -1 !important;
    display: flex !important;
    align-items: center !important;
    gap: 2rem !important;
  }
  .cj-steps-grid .cj-step-card:last-child .cj-step-header {
    margin-bottom: 0 !important;
    flex-shrink: 0 !important;
  }
  .cj-steps-grid .cj-step-card:last-child p.cj-step-desc {
    padding-left: 0 !important;
    margin: 0 !important;
  }

  .cj-steps-grid .cj-step-card { background: #fff !important; padding: 1.4rem 1.5rem !important; transition: background 0.15s !important; }
  .cj-steps-grid .cj-step-card:hover { background: #f7f7f7 !important; }
  .cj-steps-grid .cj-step-card .cj-step-header { display: flex !important; align-items: center !important; gap: 12px !important; margin-bottom: 0.75rem !important; }
  .cj-steps-grid .cj-step-card .cj-step-num { width: 28px !important; height: 28px !important; border-radius: 50% !important; border: 0.5px solid #ccc !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 12px !important; font-weight: 500 !important; color: #888 !important; flex-shrink: 0 !important; }
  .cj-steps-grid .cj-step-card:hover .cj-step-num { border-color: #999 !important; color: #111 !important; }
  .cj-steps-grid .cj-step-card .cj-step-title { font-size: 14px !important; font-weight: 500 !important; color: #111 !important; }
  .cj-steps-grid .cj-step-card p.cj-step-desc { font-size: 13px !important; color: #666 !important; line-height: 1.65 !important; padding-left: 40px !important; margin: 0 !important; }

  .cj-bottom-row { display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 16px !important; }

  .cj-tips-card { background: #f7f7f7 !important; border: 0.5px solid #e0e0e0 !important; border-radius: 12px !important; padding: 1.5rem !important; }
  .cj-tips-card h2 { font-size: 14px !important; font-weight: 500 !important; color: #111 !important; margin-bottom: 1rem !important; display: flex !important; align-items: center !important; gap: 8px !important; }
  .cj-tips-card ul.cj-tips-list { list-style: none !important; padding: 0 !important; margin: 0 !important; display: flex !important; flex-direction: column !important; gap: 8px !important; }
  .cj-tips-card ul.cj-tips-list li { font-size: 13px !important; color: #666 !important; padding-left: 18px !important; position: relative !important; line-height: 1.5 !important; list-style: none !important; }
  .cj-tips-card ul.cj-tips-list li::before { content: '—' !important; position: absolute !important; left: 0 !important; color: #ccc !important; }
  .cj-tips-card ul.cj-tips-list li::marker { display: none !important; }

  .cj-cta-card { background: #fff !important; border: 0.5px solid #ccc !important; border-radius: 12px !important; padding: 1.5rem !important; display: flex !important; flex-direction: column !important; justify-content: center !important; align-items: center !important; text-align: center !important; gap: 1rem !important; }
  .cj-cta-card p { font-size: 13px !important; color: #666 !important; line-height: 1.6 !important; margin: 0 !important; }
  .cj-cta-card .cj-cta-btn { display: inline-block !important; padding: 10px 28px !important; font-size: 14px !important; font-weight: 500 !important; color: #fff !important; background: #111 !important; border-radius: 8px !important; border: none !important; cursor: pointer !important; text-decoration: none !important; transition: opacity 0.15s !important; }
  .cj-cta-card .cj-cta-btn:hover { opacity: 0.75 !important; color: #fff !important; text-decoration: none !important; }

  @media (max-width: 768px) {
    .cj-steps-grid { grid-template-columns: repeat(2, 1fr) !important; }
    .cj-steps-grid .cj-step-card:last-child { grid-column: 1 / -1 !important; }
  }
  @media (max-width: 540px) {
    .cj-steps-grid { grid-template-columns: 1fr !important; }
    .cj-steps-grid .cj-step-card:last-child { flex-direction: column !important; align-items: flex-start !important; gap: 0.75rem !important; }
    .cj-steps-grid .cj-step-card:last-child p.cj-step-desc { padding-left: 40px !important; }
    .cj-bottom-row { grid-template-columns: 1fr !important; }
    .cj-hero h1 { font-size: 22px !important; }
  }
</style>

<section class="cj-page container">
  <div class="cj-hero">
    <span class="cj-hero-tag">Panduan Jualan</span>
    <h1>Cara Jualan di KOLUTUPA</h1>
    <p>Panduan lengkap untuk mulai berjualan baju preloved dan thrift Anda</p>
  </div>

  <div class="cj-steps-grid">
    <div class="cj-step-card">
      <div class="cj-step-header">
        <div class="cj-step-num">1</div>
        <span class="cj-step-title">Daftar atau Login</span>
      </div>
      <p class="cj-step-desc">Buat akun KOLUTUPA atau login dengan akun yang sudah ada untuk mulai berjualan.</p>
    </div>

    <div class="cj-step-card">
      <div class="cj-step-header">
        <div class="cj-step-num">2</div>
        <span class="cj-step-title">Persiapkan Produk</span>
      </div>
      <p class="cj-step-desc">Siapkan barang yang ingin dijual dengan kondisi baik dan ambil foto berkualitas dari berbagai sudut.</p>
    </div>

    <div class="cj-step-card">
      <div class="cj-step-header">
        <div class="cj-step-num">3</div>
        <span class="cj-step-title">Tambahkan Produk</span>
      </div>
      <p class="cj-step-desc">Klik "Tambah Produk" dan isi detail barang, harga, deskripsi, dan kategori dengan lengkap.</p>
    </div>

    <div class="cj-step-card">
      <div class="cj-step-header">
        <div class="cj-step-num">4</div>
        <span class="cj-step-title">Upload Foto Berkualitas</span>
      </div>
      <p class="cj-step-desc">Upload minimal 3 foto produk dari berbagai sudut untuk memberikan gambaran jelas kepada pembeli.</p>
    </div>

    <div class="cj-step-card">
      <div class="cj-step-header">
        <div class="cj-step-num">5</div>
        <span class="cj-step-title">Tunggu Pembeli</span>
      </div>
      <p class="cj-step-desc">Produk Anda akan tayang di marketplace. Respons pertanyaan pembeli dengan cepat dan profesional.</p>
    </div>

    <div class="cj-step-card">
      <div class="cj-step-header">
        <div class="cj-step-num">6</div>
        <span class="cj-step-title">Proses Pengiriman</span>
      </div>
      <p class="cj-step-desc">Setelah pembeli melakukan pembayaran, kemas produk dengan baik dan kirim sesuai alamat yang diberikan.</p>
    </div>

    <!-- Card ke-7: full width -->
    <div class="cj-step-card">
      <div class="cj-step-header">
        <div class="cj-step-num">7</div>
        <span class="cj-step-title">Terima Rating & Review</span>
      </div>
      <p class="cj-step-desc">Setelah pembeli menerima barang, minta review positif untuk meningkatkan reputasi toko Anda.</p>
    </div>
  </div>

  <div class="cj-bottom-row">
    <div class="cj-tips-card">
      <h2>&#128161; Tips Penjualan Sukses</h2>
      <ul class="cj-tips-list">
        <li>Gunakan foto yang terang dan jelas untuk menarik pembeli</li>
        <li>Tulis deskripsi detail tentang kondisi, ukuran, dan bahan barang</li>
        <li>Hargai kompetitif dengan cek harga produk serupa</li>
        <li>Respons pesan pembeli dengan cepat (dalam 24 jam)</li>
        <li>Kemasan rapi dan aman untuk kepuasan pembeli</li>
        <li>Promosikan produk terbaik Anda secara berkala</li>
      </ul>
    </div>

    <div class="cj-cta-card">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
      </svg>
      <p>Mulai jual koleksi preloved dan thrift Anda sekarang dan raih penghasilan tambahan bersama KOLUTUPA.</p>
      <a href="<?= BASE_URL ?>index.php?page=add-product" class="cj-cta-btn">Mulai Jualan Sekarang</a>
    </div>
  </div>
</section>