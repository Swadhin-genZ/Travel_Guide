<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TravelGuide</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; }
        .container { max-width: 420px; margin: 80px auto; background: #fff; padding: 32px; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 24px; color: #2c3e50; }
        label { display: block; margin-bottom: 4px; font-weight: bold; color: #555; }
        input[type=email], input[type=password] { width: 100%; padding: 10px; margin-bottom: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        .remember { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
        button { width: 100%; padding: 12px; background: #2c3e50; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #34495e; }
        .alert-error   { background: #fde8e8; color: #c0392b; padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        .alert-success { background: #e8f8e8; color: #27ae60; padding: 10px; border-radius: 4px; margin-bottom: 16px; }
        p { text-align: center; margin-top: 16px; }
        a { color: #2980b9; }
    </style>
</head>
<body>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container">
    <h2>Login</h2>

    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="POST" action="/login">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Your password" required>

        <div class="remember">
            <input type="checkbox" id="remember_me" name="remember_me">
            <label for="remember_me" style="margin:0;font-weight:normal;">Remember Me (30 days)</label>
        </div>

        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="/register">Register here</a></p>
</div>
</body>
</html>
