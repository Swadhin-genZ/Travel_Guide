// ── JS Validation: Create Form ────────────────────────────────
const createForm = document.getElementById('createForm');
if (createForm) {
    createForm.addEventListener('submit', function(e) {
        let ok = true;
        function show(id) { document.getElementById(id).style.display='block'; ok=false; }
        function hide(id) { document.getElementById(id).style.display='none'; }

        document.getElementById('c_title').value.trim()   ? hide('err_title')   : show('err_title');
        document.getElementById('c_history').value.trim() ? hide('err_history') : show('err_history');
        document.getElementById('c_country').value.trim() ? hide('err_country') : show('err_country');
        document.getElementById('c_travel').value.trim()  ? hide('err_travel')  : show('err_travel');

        if (!ok) e.preventDefault();
    });
}

// ── JS Validation: Edit Form ──────────────────────────────────
const editForm = document.getElementById('editForm');
if (editForm) {
    editForm.addEventListener('submit', function(e) {
        let ok = true;
        function show(id) { document.getElementById(id).style.display='block'; ok=false; }
        function hide(id) { document.getElementById(id).style.display='none'; }

        document.getElementById('e_title').value.trim()   ? hide('err_e_title')   : show('err_e_title');
        document.getElementById('e_history').value.trim() ? hide('err_e_history') : show('err_e_history');
        document.getElementById('e_country').value.trim() ? hide('err_e_country') : show('err_e_country');
        document.getElementById('e_travel').value.trim()  ? hide('err_e_travel')  : show('err_e_travel');

        if (!ok) e.preventDefault();
    });
}

// ── AJAX Delete using XMLHttpRequest ──────────────────────────
function showToast(msg, color) {
    const t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.style.background = color || '#27ae60';
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 3000);
}

document.querySelectorAll('.btn-delete').forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (!confirm('Delete this request?')) return;
        const id   = this.dataset.id;
        const row  = document.getElementById('row-' + id);
        const xhr  = new XMLHttpRequest();

        xhr.open('POST', 'index.php?page=scout_delete', true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const res = JSON.parse(xhr.responseText);
                if (res.success) {
                    if (row) row.remove();
                    showToast('Deleted successfully.', '#e74c3c');
                } else {
                    showToast(res.message, '#e74c3c');
                }
            }
        };
        xhr.onerror = function() {
            showToast('Network error.', '#e74c3c');
        };

        xhr.send(JSON.stringify({ id: parseInt(id) }));
    });
});