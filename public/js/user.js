// [TASK 4] General User AJAX using XMLHttpRequest

// Live search (debounced)
var searchTimer = null;
var searchBox = document.getElementById('searchBox');
if (searchBox) {
    searchBox.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            applyFilters();
        }, 400);
    });
}

// Filter dropdowns
['filterCountry', 'filterGenre', 'filterCost'].forEach(function (id) {
    var el = document.getElementById(id);
    if (el) el.addEventListener('change', applyFilters);
});

function applyFilters() {
    var q       = (document.getElementById('searchBox') || {}).value || '';
    var country = (document.getElementById('filterCountry') || {}).value || '';
    var genre   = (document.getElementById('filterGenre') || {}).value || '';
    var cost    = (document.getElementById('filterCost') || {}).value || '';

    var url = '/travel_guide/api/posts_filter.php?country=' + encodeURIComponent(country)
            + '&genre=' + encodeURIComponent(genre)
            + '&cost='  + encodeURIComponent(cost)
            + '&q='     + encodeURIComponent(q);

    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) renderPosts(res.posts);
        }
    };
    xhr.send();
}

function renderPosts(posts) {
    var grid = document.getElementById('postGrid');
    if (!grid) return;
    if (!posts.length) {
        grid.innerHTML = '<p>No destinations found.</p>';
        return;
    }
    grid.innerHTML = posts.map(function (p) {
        var img = p.image_path
            ? '<img src="/travel_guide/' + escHtml(p.image_path) + '" alt="">'
            : '';
        return '<div class="card">' + img +
            '<div class="card-body">' +
            '<span class="badge">' + escHtml(p.genre) + '</span>' +
            '<h3>' + escHtml(p.title) + '</h3>' +
            '<p class="meta">📍 ' + escHtml(p.country) + ' &bull; 💰 ' + escHtml(p.cost_level) + '</p>' +
            '<p>' + escHtml(p.short_history.substring(0, 100)) + '...</p>' +
            '<a href="index.php?action=post_detail&id=' + p.id + '" class="btn btn-sm">Read More</a>' +
            '</div></div>';
    }).join('');
}

function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// Cost Calculator [TASK 4]
function calculateCost() {
    var base      = parseFloat(document.getElementById('baseCost').textContent) || 0;
    var travelers = parseInt(document.getElementById('travelers').value) || 0;
    var days      = parseInt(document.getElementById('days').value) || 0;
    var errEl     = document.getElementById('calcError');
    var resEl     = document.getElementById('calcResult');

    if (travelers < 1 || travelers > 10) {
        errEl.textContent = 'Travelers must be between 1 and 10.';
        return;
    }
    if (days < 1) {
        errEl.textContent = 'Days must be a positive number.';
        return;
    }
    errEl.textContent = '';
    var total = base * travelers * (days / 7);
    resEl.textContent = 'Estimated Total: $' + total.toFixed(2) + ' for ' + travelers + ' traveler(s) over ' + days + ' day(s)';
}

// Submit comment [TASK 4]
function submitComment(postId) {
    var content  = (document.getElementById('commentContent') || {}).value || '';
    var errEl    = document.getElementById('commentError');

    if (!content.trim()) {
        errEl.textContent = 'Comment cannot be empty.';
        return;
    }
    if (content.length > 1000) {
        errEl.textContent = 'Comment too long (max 1000 chars).';
        return;
    }
    errEl.textContent = '';

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/travel_guide/api/comments_add.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                var c = res.comment;
                var div = document.createElement('div');
                div.className = 'comment';
                div.id = 'comment-' + c.id;
                div.innerHTML =
                    '<div class="comment-header">' +
                    '<strong>' + escHtml(c.user_name) + '</strong>' +
                    '<span class="muted">' + c.created_at + '</span>' +
                    '<button class="btn btn-sm btn-danger" onclick="deleteComment(' + c.id + ')">Delete</button>' +
                    '</div>' +
                    '<p>' + escHtml(c.content).replace(/\n/g, '<br>') + '</p>';
                var list = document.getElementById('commentsList');
                list.insertBefore(div, list.firstChild);
                document.getElementById('commentContent').value = '';
            } else {
                alert(res.message || 'Failed to post comment.');
            }
        }
    };
    xhr.send(JSON.stringify({ post_id: postId, content: content }));
}

// Delete comment [TASK 4]
function deleteComment(id) {
    if (!confirm('Delete this comment?')) return;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/travel_guide/api/comments_delete.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        var res = JSON.parse(xhr.responseText);
        if (res.success) {
            var el = document.getElementById('comment-' + id);
            if (el) el.remove();
        }
    };
    xhr.send(JSON.stringify({ id: id }));
}