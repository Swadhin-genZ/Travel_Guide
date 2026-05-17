<?php
//Register view
require 'views/layouts/header.php';
?>
<div class="form-container">
    <h2>Create Account</h2>
    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>
    <form method="POST" action="index.php?action=register" id="registerForm" novalidate>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required placeholder="Your full name">
            <span class="field-error" id="nameError"></span>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="you@example.com">
            <span class="field-error" id="emailError"></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="password" required placeholder="Min 8 characters">
            <span class="field-error" id="passError"></span>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required placeholder="Repeat password">
            <span class="field-error" id="confirmError"></span>
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="user">General User</option>
                <option value="scout">Scout</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
    <p class="form-footer">Already have an account? <a href="index.php?action=login">Login</a></p>
</div>
<script src="/travel_guide/public/js/auth.js"></script>
<?php require 'views/layouts/footer.php'; ?>