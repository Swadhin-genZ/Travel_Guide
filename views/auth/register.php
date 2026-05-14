<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TravelGuide</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; }
        .container { max-width: 480px; margin: 60px auto; background: #fff; padding: 32px; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 24px; color: #2c3e50; }
        label { display: block; margin-bottom: 4px; font-weight: bold; color: #555; }
        input, select { width: 100%; padding: 10px; margin-bottom: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        button { width: 100%; padding: 12px; background: #2c3e50; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #34495e; }
        .alert-error   { background: #fde8e8; color: #c0392b; padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        .alert-success { background: #e8f8e8; color: #27ae60; padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        .error-msg { color: #e74c3c; font-size: 12px; margin-top: -12px; margin-bottom: 8px; display: none; }
        p { text-align: center; margin-top: 16px; }
        a { color: #2980b9; }
    </style>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container">
    <h2>Create Account</h2>

    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form id="registerForm" method="POST" action="/register" novalidate>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Your full name">
        <span class="error-msg" id="nameError">Name is required.</span>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com">
        <span class="error-msg" id="emailError">Enter a valid email address.</span>

        <label for="role">Role</label>
        <select id="role" name="role">
            <option value="">-- Select Role --</option>
            <option value="user">General User</option>
            <option value="scout">Scout</option>
            <option value="admin">Admin</option>
        </select>
        <span class="error-msg" id="roleError">Please select a role.</span>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Minimum 8 characters">
        <span class="error-msg" id="passwordError">Password must be at least 8 characters.</span>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password">
        <span class="error-msg" id="confirmError">Passwords do not match.</span>

        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="/login">Login here</a></p>
</div>

<script src="/public/js/register.js"></script>
</body>
</html>
