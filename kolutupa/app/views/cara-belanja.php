<?php // app/views/cara-belanja.php ?>

<style>
  .cb-page { padding: 2.5rem 0; font-family: inherit; }

  .cb-hero { text-align: center; margin-bottom: 3rem; border-bottom: 0.5px solid rgba(0,0,0,0.1); padding-bottom: 2.5rem; }
  .cb-hero-tag { display: inline-block; font-size: 11px; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: #888; border: 0.5px solid #ccc; border-radius: 20px; padding: 4px 14px; margin-bottom: 1rem; }
  .cb-hero h1 { font-size: 26px; font-weight: 500; color: #111; line-height: 1.3; margin-bottom: 0.6rem; }
  .cb-hero p { font-size: 15px; color: #666; max-width: 480px; margin: 0 auto; }

  .cb-steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1px; background: #e0e0e0; border: 0.5px solid #e0e0e0; border-radius: 12px; overflow: hidden; margin-bottom: 2.5rem; }
  .cb-step-card { background: #fff; padding: 1.4rem 1.5rem; transition: background 0.15s; }
  .cb-step-card:hover { background: #f7f7f7; }
  .cb-step-header { display: flex; align-items: center; gap: 12px; margin-bottom: 0.75rem; }
  .cb-step-num { width: 28px; height: 28px; border-radius: 50%; border: 0.5px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 500; color: #888; flex-shrink: 0; }
  .cb-step-card:hover .cb-step-num { border-color: #999; color: #111; }
  .cb-step-title { font-size: 14px; font-weight: 500; color: #111; }
  .cb-step-desc { font-size: 13px; color: #666; line-height: 1.65; padding-left: 40px; }

  .cb-bottom-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

  .cb-tips-card { background: #f7f7f7; border: 0.5px solid #e0e0e0; border-radius: 12px; padding: 1.5rem; }
  .cb-tips-card h2 { font-size: 14px; font-weight: 500; color: #111; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }
  .cb-tips-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; }
  .cb-tips-list li { font-size: 13px; color: #666; padding-left: 18px; position: relative; line-height: 1.5; }
  .cb-tips-list li::before { content: '—'; position: absolute; left: 0; color: #ccc; }

  .cb-cta-card { background: #fff; border: 0.5px solid #ccc; border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; gap: 1rem; }
  .cb-cta-card p { font-size: 13px; color: #666; line-height: 1.6; }
  .cb-cta-btn { display: inline-block; padding: 10px 28px; font-size: 14px; font-weight: 500; color: #fff; background: #111; border-radius: 8px; border: none; cursor: pointer; text-decoration: none; transition: opacity 0.15s; }
  .cb-cta-btn:hover { opacity: 0.75; color: #fff; text-decoration: none; }

  @media (max-width: 640px) {
    .cb-bottom-row { grid-template-columns: 1fr; }
    .cb-hero h1 { font-size: 22px; }
  }
</style>

<section class="cb-page container">
  <div class="cb-hero">
    <span class="cb-hero-tag">Panduan Belanja</span>
    <h1>Cara Belanja di KOLUTUPA</h1>
    <p>Panduan lengkap untuk berbelanja baju preloved dan thrift dengan aman dan nyaman</p>
  </div>

  <div class="cb-steps-grid">
    <div class="cb-step-card">
      <div class="cb-step-header">
        <div class="cb-step-num">1</div>
        <span class="cb-step-title">Jelajahi Koleksi</span>
      </div>
      <p class="cb-step-desc">Cari produk yang Anda inginkan melalui kategori, pencarian, atau rekomendasi dari penjual terpercaya.</p>
    </div>

    <div class="cb-step-card">
      <div class="cb-step-header">
        <div class="cb-step-num">2</div>
        <span class="cb-step-title">Lihat Detail Produk</span>
      </div>
      <p class="cb-step-desc">Baca deskripsi lengkap, lihat foto dari berbagai sudut, cek kondisi barang dan ukuran yang tersedia.</p>
    </div>

    <div class="cb-step-card">
      <div class="cb-step-header">
        <div class="cb-step-num">3</div>
        <span class="cb-step-title">Tanya Penjual</span>
      </div>
      <p class="cb-step-desc">Jika ada pertanyaan, hubungi penjual melalui pesan untuk mendapatkan informasi tambahan.</p>
    </div>

    <div class="cb-step-card">
      <div class="cb-step-header">
        <div class="cb-step-num">4</div>
        <span class="cb-step-title">Tambahkan ke Keranjang</span>
      </div>
      <p class="cb-step-desc">Pilih jumlah produk dan tambahkan ke keranjang belanja Anda untuk proses checkout.</p>
    </div>

    <div class="cb-step-card">
      <div class="cb-step-header">
        <div class="cb-step-num">5</div>
        <span class="cb-step-title">Isi Data Pengiriman</span>
      </div>
      <p class="cb-step-desc">Masukkan alamat pengiriman lengkap dan pilih metode pengiriman yang sesuai dengan kebutuhan.</p>
    </div>

    <div class="cb-step-card">
      <div class="cb-step-header">
        <div class="cb-step-num">6</div>
        <span class="cb-step-title">Lakukan Pembayaran</span>
      </div>
      <p class="cb-step-desc">Pilih metode pembayaran yang tersedia (transfer bank, e-wallet, dll) dan selesaikan transaksi.</p>
    </div>

    <div class="cb-step-card">
      <div class="cb-step-header">
        <div class="cb-step-num">7</div>
        <span class="cb-step-title">Tunggu Barang Tiba</span>
      </div>
      <p class="cb-step-desc">Pantau status pengiriman dan tunggu barang sampai ke tangan Anda dalam kondisi aman.</p>
    </div>

    <div class="cb-step-card">
      <div class="cb-step-header">
        <div class="cb-step-num">8</div>
        <span class="cb-step-title">Berikan Rating & Review</span>
      </div>
      <p class="cb-step-desc">Setelah barang diterima, berikan rating dan ulasan jujur tentang pengalaman belanja Anda.</p>
    </div>
  </div>

  <div class="cb-bottom-row">
    <div class="cb-tips-card">
      <h2>&#128161; Tips Belanja Cerdas</h2>
      <ul class="cb-tips-list">
        <li>Perhatikan rating dan ulasan penjual sebelum membeli</li>
        <li>Tanyakan ke penjual tentang kondisi eksak barang jika ragu</li>
        <li>Bandingkan harga dengan penjual lain untuk mendapat harga terbaik</li>
        <li>Cek ongkos kirim termasuk asuransi paket</li>
        <li>Ambil foto paket saat diterima sebagai bukti</li>
        <li>Jangan ragu untuk mengembalikan barang jika tidak sesuai</li>
        <li>Lihat review produk dari pembeli lain untuk referensi</li>
      </ul>
    </div>

    <div class="cb-cta-card">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
      </svg>
      <p>Temukan koleksi preloved dan thrift terbaik dengan harga terjangkau dari penjual terpercaya.</p>
      <a href="<?= BASE_URL ?>index.php?page=category&cat=all" class="cb-cta-btn">Mulai Berbelanja</a>
    </div>
  </div>
</section>