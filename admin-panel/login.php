<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="auth-card">
    <h2>Login</h2>

    <form action="login_process.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</div>

</body>
</html>
