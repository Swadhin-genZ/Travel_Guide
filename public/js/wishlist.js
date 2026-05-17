//Wishlist AJAX using XMLHttpRequest

function addWishlist(postId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/travel_guide/api/wishlist_add.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                var btn = document.getElementById('wlBtn');
                if (btn) {
                    btn.textContent = '❤ Remove from Wishlist';
                    btn.className = 'btn btn-outline btn-danger';
                    btn.setAttribute('onclick', 'removeWishlist(' + postId + ')');
                }
            } else {
                alert(res.message || 'Failed to add.');
            }
        }
    };
    xhr.send(JSON.stringify({ post_id: postId }));
}

function removeWishlist(postId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/travel_guide/api/wishlist_remove.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                // If on wishlist page, remove card
                var card = document.getElementById('wl-' + postId);
                if (card) {
                    card.remove();
                    return;
                }
                // If on detail page, toggle button
                var btn = document.getElementById('wlBtn');
                if (btn) {
                    btn.textContent = '🤍 Add to Wishlist';
                    btn.className = 'btn btn-outline';
                    btn.setAttribute('onclick', 'addWishlist(' + postId + ')');
                }
            } else {
                alert(res.message || 'Failed to remove.');
            }
        }
    };
    xhr.send(JSON.stringify({ post_id: postId }));
}