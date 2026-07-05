<?php // app/views/keamanan.php ?>

<style>
  .ks-page { padding: 2.5rem 0; font-family: inherit; }

  /* Hero */
  .ks-hero { text-align: center !important; margin-bottom: 3rem !important; border-bottom: 0.5px solid rgba(0,0,0,0.1) !important; padding-bottom: 2.5rem !important; }
  .ks-hero .ks-hero-tag { display: inline-block !important; font-size: 11px !important; font-weight: 500 !important; letter-spacing: 0.1em !important; text-transform: uppercase !important; color: #888 !important; border: 0.5px solid #ccc !important; border-radius: 20px !important; padding: 4px 14px !important; margin-bottom: 1rem !important; }
  .ks-hero h1 { font-size: 26px !important; font-weight: 500 !important; color: #111 !important; line-height: 1.3 !important; margin-bottom: 0.6rem !important; }
  .ks-hero p { font-size: 15px !important; color: #666 !important; max-width: 520px !important; margin: 0 auto !important; }

  /* Cards Grid */
  .ks-cards-grid { display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 1px !important; background: #ebebeb !important; border: 0.5px solid #ebebeb !important; border-radius: 12px !important; overflow: hidden !important; margin-bottom: 2.5rem !important; }
  .ks-cards-grid .ks-card { background: #fff !important; padding: 1.5rem !important; transition: background 0.15s !important; }
  .ks-cards-grid .ks-card:hover { background: #f7f7f7 !important; }
  .ks-cards-grid .ks-card .ks-card-header { display: flex !important; align-items: center !important; gap: 10px !important; margin-bottom: 0.6rem !important; }
  .ks-cards-grid .ks-card .ks-card-icon { width: 32px !important; height: 32px !important; display: flex !important; align-items: center !important; justify-content: center !important; border: 0.5px solid #e0e0e0 !important; border-radius: 8px !important; background: #f7f7f7 !important; flex-shrink: 0 !important; }
  .ks-cards-grid .ks-card .ks-card-icon svg { width: 16px !important; height: 16px !important; stroke: #555 !important; fill: none !important; }
  .ks-cards-grid .ks-card .ks-card-title { font-size: 14px !important; font-weight: 500 !important; color: #111 !important; }
  .ks-cards-grid .ks-card p.ks-card-desc { font-size: 13px !important; color: #666 !important; line-height: 1.6 !important; margin-bottom: 0.75rem !important; padding-left: 0 !important; }
  .ks-cards-grid .ks-card ul.ks-card-list { list-style: none !important; padding: 0 !important; margin: 0 !important; display: flex !important; flex-direction: column !important; gap: 5px !important; }
  .ks-cards-grid .ks-card ul.ks-card-list li { font-size: 12px !important; color: #888 !important; padding-left: 16px !important; position: relative !important; line-height: 1.5 !important; list-style: none !important; }
  .ks-cards-grid .ks-card ul.ks-card-list li::before { content: '—' !important; position: absolute !important; left: 0 !important; color: #ccc !important; }
  .ks-cards-grid .ks-card ul.ks-card-list li::marker { display: none !important; }

  /* Last row centering */
  .ks-card-last-row { grid-column: 1 / -1 !important; display: flex !important; justify-content: center !important; background: #fff !important; padding: 0 !important; border-top: 0px !important; }
  .ks-card-last-row .ks-card { width: calc(100% / 3) !important; border: none !important; background: #fff !important; }
  .ks-card-last-row .ks-card:hover { background: #f7f7f7 !important; }

  /* FAQ */
  .ks-faq-section { margin-bottom: 2.5rem !important; }
  .ks-faq-section > h2 { font-size: 16px !important; font-weight: 500 !important; color: #111 !important; margin-bottom: 1rem !important; }
  .ks-faq-list { border: 0.5px solid #e0e0e0 !important; border-radius: 12px !important; overflow: hidden !important; padding: 0 !important; margin: 0 !important; }
  .ks-faq-list .ks-faq-item { border-bottom: 0.5px solid #e0e0e0 !important; list-style: none !important; }
  .ks-faq-list .ks-faq-item:last-child { border-bottom: none !important; }
  .ks-faq-list .ks-faq-item .ks-faq-btn { width: 100% !important; background: #fff !important; border: none !important; padding: 1rem 1.25rem !important; display: flex !important; justify-content: space-between !important; align-items: center !important; cursor: pointer !important; transition: background 0.15s !important; gap: 12px !important; box-shadow: none !important; border-radius: 0 !important; }
  .ks-faq-list .ks-faq-item .ks-faq-btn:hover { background: #f7f7f7 !important; }
  .ks-faq-list .ks-faq-item .ks-faq-btn span { font-size: 13px !important; font-weight: 500 !important; color: #111 !important; text-align: left !important; }
  .ks-faq-list .ks-faq-item .ks-faq-btn svg { width: 14px !important; height: 14px !important; stroke: #aaa !important; fill: none !important; flex-shrink: 0 !important; transition: transform 0.2s !important; }
  .ks-faq-list .ks-faq-item.open .ks-faq-btn { background: #f7f7f7 !important; }
  .ks-faq-list .ks-faq-item.open .ks-faq-btn svg { transform: rotate(180deg) !important; }
  .ks-faq-list .ks-faq-item .ks-faq-body { display: none !important; padding: 0 1.25rem 1rem !important; background: #f7f7f7 !important; }
  .ks-faq-list .ks-faq-item.open .ks-faq-body { display: block !important; }
  .ks-faq-list .ks-faq-item .ks-faq-body p { font-size: 13px !important; color: #666 !important; line-height: 1.65 !important; margin: 0 !important; }

  /* Contact */
  .ks-contact { background: #f7f7f7 !important; border: 0.5px solid #e0e0e0 !important; border-radius: 12px !important; padding: 1.75rem !important; display: flex !important; justify-content: space-between !important; align-items: center !important; gap: 1.5rem !important; flex-wrap: wrap !important; }
  .ks-contact .ks-contact-text h2 { font-size: 15px !important; font-weight: 500 !important; color: #111 !important; margin-bottom: 0.4rem !important; }
  .ks-contact .ks-contact-text p { font-size: 13px !important; color: #666 !important; line-height: 1.6 !important; max-width: 380px !important; margin: 0 !important; }
  .ks-contact .ks-contact-info { display: flex !important; flex-direction: column !important; gap: 8px !important; }
  .ks-contact .ks-contact-row { display: flex !important; align-items: center !important; gap: 8px !important; }
  .ks-contact .ks-contact-row svg { width: 14px !important; height: 14px !important; stroke: #888 !important; fill: none !important; flex-shrink: 0 !important; }
  .ks-contact .ks-contact-row span { font-size: 13px !important; color: #444 !important; }

  @media (max-width: 640px) {
    .ks-hero h1 { font-size: 22px !important; }
    .ks-cards-grid { grid-template-columns: 1fr !important; }
    .ks-card-last-row .ks-card { width: 100% !important; }
    .ks-contact { flex-direction: column !important; align-items: flex-start !important; }
  }
</style>

<section class="ks-page container">

  <div class="ks-hero">
    <span class="ks-hero-tag">Keamanan</span>
    <h1>Keamanan dan Perlindungan di KOLUTUPA</h1>
    <p>Kami berkomitmen untuk memberikan pengalaman belanja dan jualan yang aman, nyaman, dan terpercaya</p>
  </div>

  <div class="ks-cards-grid">

    <div class="ks-card">
      <div class="ks-card-header">
        <div class="ks-card-icon">
          <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <span class="ks-card-title">Perlindungan Pembeli</span>
      </div>
      <p class="ks-card-desc">Setiap transaksi dilindungi dengan sistem escrow hingga pembeli mengkonfirmasi barang dalam kondisi baik.</p>
      <ul class="ks-card-list">
        <li>Dana ditahan oleh sistem KOLUTUPA selama pengiriman</li>
        <li>Pembeli dapat mengembalikan barang jika tidak sesuai</li>
        <li>Garansi uang kembali 100% jika barang hilang atau rusak</li>
      </ul>
    </div>

    <div class="ks-card">
      <div class="ks-card-header">
        <div class="ks-card-icon">
          <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <span class="ks-card-title">Verifikasi Penjual</span>
      </div>
      <p class="ks-card-desc">Semua penjual telah melalui proses verifikasi untuk memastikan kredibilitas mereka.</p>
      <ul class="ks-card-list">
        <li>Verifikasi identitas dan data pribadi</li>
        <li>Riwayat transaksi yang transparan</li>
        <li>Rating dan review dari pembeli nyata</li>
        <li>Sistem poin reputasi penjual</li>
      </ul>
    </div>

    <div class="ks-card">
      <div class="ks-card-header">
        <div class="ks-card-icon">
          <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        </div>
        <span class="ks-card-title">Keamanan Data Pribadi</span>
      </div>
      <p class="ks-card-desc">Data pribadi Anda dilindungi dengan enkripsi tingkat enterprise dan sistem keamanan berlapis.</p>
      <ul class="ks-card-list">
        <li>Enkripsi SSL/TLS untuk semua koneksi</li>
        <li>Password terenkripsi dengan hashing yang aman</li>
        <li>Perlindungan dari fraud dan phishing</li>
        <li>Kebijakan privasi yang ketat</li>
      </ul>
    </div>

    <div class="ks-card">
      <div class="ks-card-header">
        <div class="ks-card-icon">
          <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        </div>
        <span class="ks-card-title">Keamanan Pengiriman</span>
      </div>
      <p class="ks-card-desc">Kami bekerja sama dengan kurir terpercaya untuk memastikan barang sampai dengan aman.</p>
      <ul class="ks-card-list">
        <li>Tracking real-time untuk setiap paket</li>
        <li>Asuransi pengiriman wajib untuk semua transaksi</li>
        <li>Kemasan standar untuk keamanan produk</li>
        <li>Klaim asuransi jika paket hilang atau rusak</li>
      </ul>
    </div>

    <div class="ks-card">
      <div class="ks-card-header">
        <div class="ks-card-icon">
          <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
        </div>
        <span class="ks-card-title">Resolusi Sengketa</span>
      </div>
      <p class="ks-card-desc">Tim customer service kami siap membantu menyelesaikan masalah dengan adil dan transparan.</p>
      <ul class="ks-card-list">
        <li>Sistem mediasi untuk konflik pembeli-penjual</li>
        <li>Proses appeal yang jelas dan objektif</li>
        <li>Customer support 24/7 untuk membantu Anda</li>
        <li>Refund otomatis jika masalah tidak terselesaikan</li>
      </ul>
    </div>

    <div class="ks-card">
      <div class="ks-card-header">
        <div class="ks-card-icon">
          <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        </div>
        <span class="ks-card-title">Pencegahan Penipuan</span>
      </div>
      <p class="ks-card-desc">Teknologi AI dan monitoring manual mencegah aktivitas mencurigakan di platform kami.</p>
      <ul class="ks-card-list">
        <li>Deteksi otomatis transaksi mencurigakan</li>
        <li>Blokir akun yang melakukan fraud</li>
        <li>Sistem pelaporan untuk produk atau seller ilegal</li>
        <li>Kolaborasi dengan pihak berwenang saat diperlukan</li>
      </ul>
    </div>

    <div class="ks-card-last-row">
      <div class="ks-card">
        <div class="ks-card-header">
          <div class="ks-card-icon">
            <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
          </div>
          <span class="ks-card-title">Komunitas yang Aman</span>
        </div>
        <p class="ks-card-desc">Kami membangun komunitas sehat dengan regulasi ketat terhadap perilaku tidak etis.</p>
        <ul class="ks-card-list">
          <li>Review terverifikasi dari pembeli nyata</li>
          <li>Sistem rating transparan untuk seller dan produk</li>
          <li>Moderasi konten untuk kenyamanan komunitas</li>
          <li>Zero tolerance terhadap pelecehan atau diskriminasi</li>
        </ul>
      </div>
    </div>

  </div>

  <!-- FAQ -->
  <div class="ks-faq-section">
    <h2>Pertanyaan Umum Tentang Keamanan</h2>
    <div class="ks-faq-list">

      <div class="ks-faq-item">
        <button class="ks-faq-btn" onclick="ksFaqToggle(this)">
          <span>Bagaimana jika barang tidak sampai?</span>
          <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="ks-faq-body">
          <p>Jika barang tidak sampai dalam waktu yang disepakati, Anda dapat membuka klaim. KOLUTUPA akan menginvestigasi dan memberikan refund atau pengiriman ulang.</p>
        </div>
      </div>

      <div class="ks-faq-item">
        <button class="ks-faq-btn" onclick="ksFaqToggle(this)">
          <span>Apa yang harus saya lakukan jika menerima barang cacat?</span>
          <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="ks-faq-body">
          <p>Dokumentasikan kerusakan dengan foto dan hubungi penjual dalam 48 jam. Jika tidak ada kesepakatan, KOLUTUPA akan memediasi dan memberikan solusi terbaik.</p>
        </div>
      </div>

      <div class="ks-faq-item">
        <button class="ks-faq-btn" onclick="ksFaqToggle(this)">
          <span>Bagaimana data kartu kredit saya dilindungi?</span>
          <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="ks-faq-body">
          <p>Kami tidak menyimpan data kartu kredit Anda. Pembayaran diproses melalui gateway pembayaran tersertifikasi dengan enkripsi tingkat bank.</p>
        </div>
      </div>

      <div class="ks-faq-item">
        <button class="ks-faq-btn" onclick="ksFaqToggle(this)">
          <span>Bisakah saya melaporkan penjual yang mencurigakan?</span>
          <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="ks-faq-body">
          <p>Ya, gunakan tombol "Laporkan" di profil penjual atau halaman produk. Tim moderasi kami akan menginvestigasi laporan Anda dengan serius.</p>
        </div>
      </div>

      <div class="ks-faq-item">
        <button class="ks-faq-btn" onclick="ksFaqToggle(this)">
          <span>Apa penalti untuk penjual yang nakal?</span>
          <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="ks-faq-body">
          <p>Penalti bervariasi dari peringatan, suspend akun sementara, hingga penghapusan akun permanen dan penuntutan hukum jika diperlukan.</p>
        </div>
      </div>

    </div>
  </div>

  <!-- Contact -->
  <div class="ks-contact">
    <div class="ks-contact-text">
      <h2>Hubungi Tim Keamanan Kami</h2>
      <p>Jika Anda menemukan aktivitas mencurigakan atau memiliki pertanyaan keamanan, segera hubungi kami.</p>
    </div>
    <div class="ks-contact-info">
      <div class="ks-contact-row">
        <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <span>security@kolutupa.com</span>
      </div>
      <div class="ks-contact-row">
        <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 .94h3a2 2 0 012 1.72c.13 1 .37 1.98.72 2.92a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.22-1.22a2 2 0 012.11-.45c.94.35 1.92.59 2.92.72a2 2 0 011.72 2.04z"/></svg>
        <span>1-800-KOLUTUPA</span>
      </div>
      <!-- <div class="ks-contact-row">
        <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        <span>Live Chat — Tersedia 24/7</span>
      </div> -->
    </div>
  </div>

</section>

<script>
function ksFaqToggle(btn) {
  var item = btn.closest('.ks-faq-item');
  var isOpen = item.classList.contains('open');
  document.querySelectorAll('.ks-faq-item.open').forEach(function(el) {
    el.classList.remove('open');
  });
  if (!isOpen) item.classList.add('open');
}
</script>