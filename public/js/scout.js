// [TASK 2] Scout AJAX using XMLHttpRequest

// Delete pending request
function deleteRequest(id) {
    if (!confirm('Delete this request?')) return;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/travel_guide/api/scout_delete_request.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                var row = document.getElementById('req-' + id);
                if (row) row.remove();
            } else {
                alert('Could not delete request.');
            }
        }
    };
    xhr.send(JSON.stringify({ id: id }));
}

// Scout form JS validation
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('scoutForm');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        var valid = true;
        var title = form.querySelector('[name="title"]');
        var titleErr = document.getElementById('titleError');
        if (title && title.value.trim().length < 3) {
            if (titleErr) titleErr.textContent = 'Title must be at least 3 characters.';
            valid = false;
        } else if (titleErr) {
            titleErr.textContent = '';
        }

        var history = form.querySelector('[name="short_history"]');
        var histErr = document.getElementById('historyError');
        if (history && history.value.trim().length < 20) {
            if (histErr) histErr.textContent = 'Please provide a description (min 20 chars).';
            valid = false;
        } else if (histErr) {
            histErr.textContent = '';
        }

        if (!valid) e.preventDefault();
    });
});