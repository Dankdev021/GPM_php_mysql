<!-- views/login.php -->

<?php
$pageTitle = 'Login';
$customCSS = ['login/style.css'];
include 'header.php';
?>

<div class="container">
    <div class="login-container">
        <h2 class="text-center">Login</h2>
        <form action="/oficina/controllers/UserController.php" method="POST"> <!-- Caminho absoluto -->
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</div>

<?php
$customJS = ['login/script.js'];
include 'footer.php';
?>
