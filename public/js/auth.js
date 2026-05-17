//Auth & Profile JS Validation

document.addEventListener('DOMContentLoaded', function () {

    //Registration Form
    var registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            var valid = true;

            var name = registerForm.querySelector('[name="name"]');
            var nameErr = document.getElementById('nameError');
            if (name && name.value.trim().length < 2) {
                nameErr.textContent = 'Name must be at least 2 characters.';
                valid = false;
            } else if (nameErr) {
                nameErr.textContent = '';
            }

            var email = registerForm.querySelector('[name="email"]');
            var emailErr = document.getElementById('emailError');
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailPattern.test(email.value)) {
                emailErr.textContent = 'Please enter a valid email.';
                valid = false;
            } else if (emailErr) {
                emailErr.textContent = '';
            }

            var pass = document.getElementById('password');
            var passErr = document.getElementById('passError');
            if (pass && pass.value.length < 8) {
                passErr.textContent = 'Password must be at least 8 characters.';
                valid = false;
            } else if (passErr) {
                passErr.textContent = '';
            }

            var confirm = document.getElementById('confirm_password');
            var confirmErr = document.getElementById('confirmError');
            if (confirm && pass && confirm.value !== pass.value) {
                confirmErr.textContent = 'Passwords do not match.';
                valid = false;
            } else if (confirmErr) {
                confirmErr.textContent = '';
            }

            if (!valid) e.preventDefault();
        });
    }

    //Profile Form 
    var profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function (e) {
            var valid = true;

            var name = profileForm.querySelector('[name="name"]');
            var nameErr = document.getElementById('nameError');
            if (name && name.value.trim().length < 2) {
                nameErr.textContent = 'Name required.';
                valid = false;
            } else if (nameErr) {
                nameErr.textContent = '';
            }

            var newPass = document.getElementById('new_password');
            var passErr = document.getElementById('passError');
            if (newPass && newPass.value.length > 0 && newPass.value.length < 8) {
                passErr.textContent = 'New password must be at least 8 characters.';
                valid = false;
            } else if (passErr) {
                passErr.textContent = '';
            }

            if (!valid) e.preventDefault();
        });
    }
});