function showToast(msg, color) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    toast.textContent = msg;
    toast.style.background = color || '#27ae60';
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

// ── Wishlist page: Remove buttons ────────────────────────────
document.querySelectorAll('.btn-remove').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const postId = this.dataset.postId;
        if (!confirm('Remove this destination from your wishlist?')) return;

        fetch('/api/wishlist/remove', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ post_id: parseInt(postId) })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById('row-' + postId);
                if (row) row.remove();
                showToast('Removed from wishlist.', '#e74c3c');

                // If table is now empty
                const tbody = document.querySelector('#wishlistTable tbody');
                if (tbody && tbody.children.length === 0) {
                    location.reload();
                }
            } else {
                showToast(data.message || 'Error removing item.', '#e74c3c');
            }
        })
        .catch(() => showToast('Network error.', '#e74c3c'));
    });
});

// ── Home page: Add to Wishlist buttons ───────────────────────
document.querySelectorAll('.wishlist-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const postId = this.dataset.postId;
        const self   = this;

        fetch('/api/wishlist/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ post_id: parseInt(postId) })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                self.textContent = '✅ Added';
                self.disabled = true;
                self.style.background = '#27ae60';
                showToast('Added to wishlist!');
            } else {
                showToast(data.message || 'Could not add.', '#e67e22');
            }
        })
        .catch(() => showToast('Network error.', '#e74c3c'));
    });
});
