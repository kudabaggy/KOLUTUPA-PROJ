<?php // app/views/messages/index.php ?>

<style>
  .mp-page { padding: 0 !important; font-family: inherit; }

  /* Layout */
  .mp-layout {
    display: flex !important;
    height: calc(100vh - 80px) !important;
    border: 0.5px solid #e0e0e0 !important;
    border-radius: 12px !important;
    overflow: hidden !important;
    background: #fff !important;
  }

  /* Sidebar */
  .mp-sidebar {
    width: 300px !important;
    flex-shrink: 0 !important;
    border-right: 0.5px solid #e0e0e0 !important;
    display: flex !important;
    flex-direction: column !important;
    background: #fafafa !important;
  }
  .mp-sidebar-header {
    padding: 1.25rem 1.25rem 1rem !important;
    border-bottom: 0.5px solid #e0e0e0 !important;
  }
  .mp-sidebar-header h2 {
    font-size: 15px !important;
    font-weight: 500 !important;
    color: #111 !important;
    margin: 0 !important;
  }
  .mp-conv-list {
    overflow-y: auto !important;
    flex: 1 !important;
  }
  .mp-conv-item {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    padding: 0.9rem 1.25rem !important;
    border-bottom: 0.5px solid #f0f0f0 !important;
    text-decoration: none !important;
    transition: background 0.12s !important;
    cursor: pointer !important;
  }
  .mp-conv-item:hover { background: #f0f0f0 !important; }
  .mp-conv-item.active { background: #ebebeb !important; }
  .mp-conv-avatar {
    width: 38px !important;
    height: 38px !important;
    border-radius: 50% !important;
    object-fit: cover !important;
    flex-shrink: 0 !important;
    border: 0.5px solid #e0e0e0 !important;
  }
  .mp-conv-info { overflow: hidden !important; flex: 1 !important; }
  .mp-conv-info strong {
    display: block !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #111 !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
  }
  .mp-conv-info p {
    font-size: 12px !important;
    color: #999 !important;
    margin: 2px 0 0 !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
  }

  /* Thread Panel */
  .mp-thread {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    overflow: hidden !important;
    background: #fff !important;
  }

  /* Thread Header */
  .mp-thread-header {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    padding: 1rem 1.25rem !important;
    border-bottom: 0.5px solid #e0e0e0 !important;
    flex-shrink: 0 !important;
  }
  .mp-thread-header img {
    width: 34px !important;
    height: 34px !important;
    border-radius: 50% !important;
    object-fit: cover !important;
    border: 0.5px solid #e0e0e0 !important;
  }
  .mp-thread-header strong {
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #111 !important;
  }

  /* Nego Card */
  .mp-nego {
    margin: 0.75rem 1.25rem !important;
    background: #f7f7f7 !important;
    border: 0.5px solid #e0e0e0 !important;
    border-radius: 10px !important;
    padding: 1rem !important;
    flex-shrink: 0 !important;
  }
  .mp-nego-header {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    margin-bottom: 0.5rem !important;
  }
  .mp-nego-header strong {
    font-size: 11px !important;
    font-weight: 500 !important;
    color: #888 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
  }
  .mp-nego-header span {
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #111 !important;
  }
  .mp-nego-offer {
    font-size: 13px !important;
    color: #444 !important;
    margin: 0 0 4px !important;
  }
  .mp-nego-status {
    font-size: 12px !important;
    color: #888 !important;
    margin: 0 0 0.75rem !important;
  }
  .mp-nego-status strong { color: #333 !important; }
  .mp-nego-actions {
    display: flex !important;
    gap: 8px !important;
  }
  .mp-nego-actions form { margin: 0 !important; }
  .mp-nego-note {
    font-size: 12px !important;
    color: #888 !important;
    margin: 0 !important;
  }
  .mp-nego-note.accepted { color: #444 !important; }
  .mp-nego-note.rejected { color: #bbb !important; }

  /* Messages area */
  .mp-messages {
    flex: 1 !important;
    overflow-y: auto !important;
    padding: 1.25rem !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 8px !important;
  }

  /* Bubbles */
  .mp-bubble {
    max-width: 65% !important;
    display: flex !important;
    flex-direction: column !important;
  }
  .mp-bubble.sent { align-self: flex-end !important; align-items: flex-end !important; }
  .mp-bubble.received { align-self: flex-start !important; align-items: flex-start !important; }
  .mp-bubble p {
    margin: 0 !important;
    font-size: 13px !important;
    line-height: 1.55 !important;
    padding: 0.6rem 0.9rem !important;
    border-radius: 16px !important;
    word-break: break-word !important;
  }
  .mp-bubble.sent p {
    background: #111 !important;
    color: #fff !important;
    border-bottom-right-radius: 4px !important;
  }
  .mp-bubble.received p {
    background: #f0f0f0 !important;
    color: #111 !important;
    border-bottom-left-radius: 4px !important;
  }
  .mp-bubble small {
    font-size: 11px !important;
    color: #bbb !important;
    margin-top: 3px !important;
    padding: 0 4px !important;
  }

  /* Message form */
  .mp-form {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding: 0.9rem 1.25rem !important;
    border-top: 0.5px solid #e0e0e0 !important;
    flex-shrink: 0 !important;
  }
  .mp-form input[type="text"] {
    flex: 1 !important;
    border: 0.5px solid #e0e0e0 !important;
    border-radius: 20px !important;
    padding: 0.55rem 1rem !important;
    font-size: 13px !important;
    color: #111 !important;
    background: #f7f7f7 !important;
    outline: none !important;
    transition: border-color 0.15s, background 0.15s !important;
  }
  .mp-form input[type="text"]:focus {
    border-color: #aaa !important;
    background: #fff !important;
  }
  .mp-send-btn {
    width: 36px !important;
    height: 36px !important;
    border-radius: 50% !important;
    background: #111 !important;
    border: none !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-shrink: 0 !important;
    transition: opacity 0.15s !important;
  }
  .mp-send-btn:hover { opacity: 0.75 !important; }
  .mp-send-btn svg {
    width: 15px !important;
    height: 15px !important;
    stroke: #fff !important;
    fill: none !important;
  }

  /* Buttons override */
  .mp-nego .btn-primary, .mp-nego .btn-outline {
    display: inline-block !important;
    padding: 5px 14px !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    border-radius: 6px !important;
    cursor: pointer !important;
    text-decoration: none !important;
    border: 0.5px solid #111 !important;
    transition: opacity 0.15s !important;
  }
  .mp-nego .btn-primary {
    background: #111 !important;
    color: #fff !important;
  }
  .mp-nego .btn-primary:hover { opacity: 0.75 !important; }
  .mp-nego .btn-outline {
    background: #fff !important;
    color: #111 !important;
  }
  .mp-nego .btn-outline:hover { background: #f0f0f0 !important; }

  /* Empty state */
  .mp-empty {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    color: #bbb !important;
  }
  .mp-empty svg {
    width: 40px !important;
    height: 40px !important;
    stroke: #ddd !important;
    fill: none !important;
    margin-bottom: 0.5rem !important;
  }
  .mp-empty h3 {
    font-size: 15px !important;
    font-weight: 500 !important;
    color: #888 !important;
    margin: 0 !important;
  }
  .mp-empty p {
    font-size: 13px !important;
    color: #bbb !important;
    margin: 0 !important;
  }

  @media (max-width: 640px) {
    .mp-layout { height: calc(100vh - 60px) !important; border-radius: 0 !important; }
    .mp-sidebar { width: 80px !important; }
    .mp-conv-info { display: none !important; }
    .mp-conv-item { justify-content: center !important; padding: 0.75rem !important; }
  }
</style>

<div class="mp-page">
  <div class="mp-layout">

    <!-- Sidebar -->
    <div class="mp-sidebar">
      <div class="mp-sidebar-header">
        <h2>Pesan</h2>
      </div>
      <div class="mp-conv-list">
        <?php foreach ($conversations as $conv): ?>
        <a href="<?= BASE_URL ?>index.php?page=messages&user=<?= $conv['other_user_id'] ?>"
           class="mp-conv-item <?= $otherId == $conv['other_user_id'] ? 'active' : '' ?>">
          <img class="mp-conv-avatar" src="<?= avatarUrl($conv['other_avatar']) ?>" alt="">
          <div class="mp-conv-info">
            <strong><?= sanitize($conv['other_name']) ?></strong>
            <p><?= sanitize(substr($conv['content'], 0, 38)) ?><?= strlen($conv['content']) > 38 ? '…' : '' ?></p>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Thread -->
    <div class="mp-thread">
      <?php if ($otherId && $otherUser): ?>

        <!-- Header -->
        <div class="mp-thread-header">
          <img src="<?= avatarUrl($otherUser['avatar']) ?>" alt="">
          <strong><?= sanitize($otherUser['name']) ?></strong>
        </div>

        <!-- Nego Card -->
        <?php if (!empty($negotiation)): ?>
        <div class="mp-nego">
          <div class="mp-nego-header">
            <strong>Nego</strong>
            <span><?= sanitize($negotiation['product_title']) ?></span>
          </div>
          <p class="mp-nego-offer">Penawaran: <?= formatRupiah($negotiation['offered_price']) ?></p>
          <p class="mp-nego-status">Status: <strong><?= ucfirst(str_replace('_', ' ', $negotiation['status'])) ?></strong></p>

          <?php if ($negotiation['status'] === 'pending'): ?>
            <?php if ($negotiation['seller_id'] === $_SESSION['user_id']): ?>
            <div class="mp-nego-actions">
              <form method="POST" action="<?= BASE_URL ?>index.php?action=accept-nego">
                <?= csrf() ?>
                <input type="hidden" name="nego_id" value="<?= $negotiation['id'] ?>">
                <button type="submit" class="btn-primary btn-sm">Setujui</button>
              </form>
              <form method="POST" action="<?= BASE_URL ?>index.php?action=reject-nego">
                <?= csrf() ?>
                <input type="hidden" name="nego_id" value="<?= $negotiation['id'] ?>">
                <button type="submit" class="btn-outline btn-sm">Tolak</button>
              </form>
            </div>
            <?php else: ?>
            <p class="mp-nego-note">Menunggu jawaban penjual.</p>
            <?php endif; ?>

          <?php elseif ($negotiation['status'] === 'accepted'): ?>
            <p class="mp-nego-note accepted">Penawaran disetujui. Silakan lanjut ke pembelian.</p>
            <?php if ($negotiation['buyer_id'] === $_SESSION['user_id']): ?>
                <?php if (empty($orderExists)): ?>
                <a href="<?= BASE_URL ?>index.php?page=checkout&product_id=<?= $negotiation['product_id'] ?>&nego_id=<?= $negotiation['id'] ?>" class="btn-primary btn-sm" style="margin-top:8px;display:inline-block">Beli sekarang</a>
                <?php else: ?>
                <p class="mp-nego-note">Pesanan untuk produk ini sudah dibuat.</p>
                <?php endif; ?>
            <?php endif; ?>

          <?php elseif ($negotiation['status'] === 'rejected'): ?>
            <p class="mp-nego-note rejected">Penawaran ditolak oleh penjual.</p>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Messages -->
        <div class="mp-messages" id="threadMessages">
          <?php foreach ($thread as $msg): ?>
          <div class="mp-bubble <?= $msg['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received' ?>">
            <p><?= nl2br(sanitize($msg['content'])) ?></p>
            <small><?= timeAgo($msg['created_at']) ?></small>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Input -->
        <form method="POST" action="<?= BASE_URL ?>index.php?action=send-message" class="mp-form">
          <?= csrf() ?>
          <input type="hidden" name="receiver_id" value="<?= $otherId ?>">
          <input type="text" name="content" placeholder="Ketik pesan..." required>
          <button type="submit" class="mp-send-btn">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
          </button>
        </form>

      <?php else: ?>
        <div class="mp-empty">
          <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
          </svg>
          <h3>Selamat datang di Pesan</h3>
          <p>Pilih percakapan untuk mulai ngobrol</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
const tm = document.getElementById('threadMessages');
if (tm) tm.scrollTop = tm.scrollHeight;
</script>