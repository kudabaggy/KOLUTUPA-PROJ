<?php // app/views/wallet.php ?>
<div class="wallet-page">
    <div class="wallet-card">
        <div class="wallet-header">
            <span>Saldo Tertunda</span>
            <span><?= formatRupiah($user['wallet_balance'] ?? 0) ?></span>
        </div>
        <div class="wallet-promo">
            <span>⚡</span>
            <div>
                <strong>Mau cuan lebih?</strong>
                <p>Semakin banyak upload barang, makin gampang pembeli menemukanmu.</p>
            </div>
        </div>
        <div class="wallet-balance">
            <p class="balance-amount"><?= formatRupiah($user['wallet_balance'] ?? 0) ?></p>
            <p>Saldo Tersedia</p>
        </div>
        <button class="btn-ghost btn-full" disabled>Cairkan saldo</button>
    </div>
    <div class="wallet-history">
        <p class="history-month"><?= date('F Y') ?></p>
        <div class="history-item">
            <p>Saldo Awal</p>
            <small><?= date('d M Y') ?></small>
        </div>
    </div>
</div>
