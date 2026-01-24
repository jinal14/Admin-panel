<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="auth-card">
    <h2>Create Account</h2>

    <form action="signup_process.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="date" name="dob" required>

        <!-- ✅ Profile Picture Upload -->
        <input type="file" name="profile_pic" accept="image/*">

        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
