document.getElementById('registerForm').addEventListener('submit', function (e) {
    let valid = true;

    const name    = document.getElementById('name');
    const email   = document.getElementById('email');
    const role    = document.getElementById('role');
    const pw      = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');

    // Reset errors
    document.querySelectorAll('.error-msg').forEach(el => el.style.display = 'none');

    if (name.value.trim() === '') {
        document.getElementById('nameError').style.display = 'block';
        valid = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value.trim())) {
        document.getElementById('emailError').style.display = 'block';
        valid = false;
    }

    if (role.value === '') {
        document.getElementById('roleError').style.display = 'block';
        valid = false;
    }

    if (pw.value.length < 8) {
        document.getElementById('passwordError').style.display = 'block';
        valid = false;
    }

    if (pw.value !== confirm.value) {
        document.getElementById('confirmError').style.display = 'block';
        valid = false;
    }

    if (!valid) e.preventDefault();
});
