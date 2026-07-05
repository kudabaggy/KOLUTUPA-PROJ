// KOLUTUPA - Main JS

document.addEventListener('DOMContentLoaded', () => {

    // ── Avatar dropdown ────────────────────────────────
    const avatarBtn = document.getElementById('avatarBtn');
    const dropdown  = document.getElementById('dropdownMenu');
    if (avatarBtn && dropdown) {
        avatarBtn.addEventListener('click', e => {
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });
        document.addEventListener('click', () => dropdown.classList.remove('open'));
    }

    // ── Like button (AJAX) ─────────────────────────────
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
            e.preventDefault();
            const productId = btn.dataset.productId;
            if (!productId) return;

            try {
                const endpoint = new URL('index.php?action=toggle-like', window.location.href);
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `product_id=${encodeURIComponent(productId)}`
                });

                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await res.json();
                const liked = Boolean(data.liked);

                btn.classList.toggle('liked', liked);
                if (liked) {
                    btn.style.borderColor = '#e74c3c';
                    btn.style.color = '#e74c3c';
                    btn.innerHTML = '<i class="fa-solid fa-heart"></i> Hapus dari Favorit';
                } else {
                    btn.style.borderColor = '';
                    btn.style.color = '';
                    btn.innerHTML = '<i class="fa-regular fa-heart"></i> Simpan ke Favorit';
                }
            } catch (error) {
                console.error('Favorite toggle error:', error);
                alert('Gagal memperbarui favorit. Silakan coba lagi.');
            }
        });
    });

    // ── Add to cart feedback ───────────────────────────
    document.querySelectorAll('[data-cart-form]').forEach(form => {
        form.addEventListener('submit', async e => {
            e.preventDefault();
            const btn = form.querySelector('button');
            const original = btn.textContent;
            btn.textContent = 'Ditambahkan! ✓';
            btn.disabled = true;
            await fetch(form.action, { method: 'POST', body: new FormData(form) });
            setTimeout(() => { btn.textContent = original; btn.disabled = false; }, 2000);
        });
    });

    // ── Image gallery ─────────────────────────────────
    window.switchImage = (el) => {
        const main = document.getElementById('mainImg');
        if (main) main.src = el.src;
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    };

    // ── Scroll thread to bottom ───────────────────────
    const thread = document.getElementById('threadMessages');
    if (thread) thread.scrollTop = thread.scrollHeight;

    // ── Flash auto dismiss ─────────────────────────────
    const flash = document.querySelector('.flash-message');
    if (flash) setTimeout(() => flash.remove(), 4000);

    // ── Lazy load images ─────────────────────────────
    if ('IntersectionObserver' in window) {
        const imgs = document.querySelectorAll('img[loading="lazy"]');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.src = e.target.dataset.src || e.target.src; } });
        });
        imgs.forEach(img => observer.observe(img));
    }

    // ── Profile tabs via URL hash ─────────────────────
    const hash = window.location.hash.replace('#', '');
    if (hash) {
        const btn = document.querySelector(`.tab-btn[data-tab="${hash}"]`);
        if (btn) btn.click();
    }
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => { window.history.replaceState(null, '', '#' + btn.dataset.tab); });
    });

});
