document.getElementById('profileForm').addEventListener('submit', function (e) {
    let valid = true;

    document.querySelectorAll('.error-msg').forEach(el => el.style.display = 'none');

    const name    = document.getElementById('name');
    const email   = document.getElementById('email');
    const newPw   = document.getElementById('new_password');
    const confirm = document.getElementById('confirm_password');

    if (name.value.trim() === '') {
        document.getElementById('nameError').style.display = 'block';
        valid = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value.trim())) {
        document.getElementById('emailError').style.display = 'block';
        valid = false;
    }

    // Only validate password fields if user typed something
    if (newPw.value.length > 0) {
        if (newPw.value.length < 8) {
            document.getElementById('newPwError').style.display = 'block';
            valid = false;
        }
        if (newPw.value !== confirm.value) {
            document.getElementById('confirmError').style.display = 'block';
            valid = false;
        }
    }

    if (!valid) e.preventDefault();
});
