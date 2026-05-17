<?php
//Login view
require 'views/layouts/header.php';
?>
<div class="form-container">
    <h2>Login</h2>
    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>
    <form method="POST" action="index.php?action=login" id="loginForm">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="you@example.com">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Password">
        </div>
        <div class="form-group checkbox-row">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me (30 days)</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
    <p class="form-footer">Don't have an account? <a href="index.php?action=register">Register</a></p>
</div>
<?php require 'views/layouts/footer.php'; ?>