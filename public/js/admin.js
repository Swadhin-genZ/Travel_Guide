// [TASK 3] Admin AJAX using XMLHttpRequest

// Toggle user verification
function toggleVerify(userId, newStatus) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/travel_guide/api/admin_verify_user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                var badge = document.getElementById('vstatus-' + userId);
                var row   = document.getElementById('user-' + userId);
                if (badge) {
                    badge.textContent = res.new_status ? 'Verified' : 'Pending';
                    badge.className   = 'status-badge status-' + (res.new_status ? 'approved' : 'pending');
                }
                // Swap button
                if (row) {
                    var btn = row.querySelector('button[onclick^="toggleVerify"]');
                    if (btn) {
                        if (res.new_status) {
                            btn.textContent = 'Unverify';
                            btn.className   = 'btn btn-sm btn-outline';
                            btn.setAttribute('onclick', 'toggleVerify(' + userId + ', 0)');
                        } else {
                            btn.textContent = 'Verify';
                            btn.className   = 'btn btn-sm btn-success';
                            btn.setAttribute('onclick', 'toggleVerify(' + userId + ', 1)');
                        }
                    }
                }
            }
        }
    };
    xhr.send(JSON.stringify({ id: userId, status: newStatus }));
}

// Approve post request
function approvePost(id) {
    if (!confirm('Approve this post request?')) return;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/travel_guide/api/admin_approve_post.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        var res = JSON.parse(xhr.responseText);
        if (res.success) {
            var row = document.getElementById('preq-' + id);
            if (row) row.remove();
            alert('Post approved and published!');
        } else {
            alert(res.message || 'Error');
        }
    };
    xhr.send(JSON.stringify({ id: id, action: 'approve' }));
}

// Reject post request
function rejectPost(id) {
    var reason = prompt('Enter rejection reason (optional):') || '';
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/travel_guide/api/admin_approve_post.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        var res = JSON.parse(xhr.responseText);
        if (res.success) {
            var row = document.getElementById('preq-' + id);
            if (row) row.remove();
        }
    };
    xhr.send(JSON.stringify({ id: id, action: 'reject', reason: reason }));
}

// Admin delete comment
function adminDeleteComment(id) {
    if (!confirm('Delete this comment?')) return;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/travel_guide/api/admin_delete_comment.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        var res = JSON.parse(xhr.responseText);
        if (res.success) {
            var row = document.getElementById('cmt-' + id);
            if (row) row.remove();
        }
    };
    xhr.send(JSON.stringify({ id: id }));
}